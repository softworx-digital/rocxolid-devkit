<?php

namespace Softworx\RocXolid\DevKit\Http\Controllers;

use App;
use Artisan;
use Validator;
use ViewHelper;
use Illuminate\Http\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Softworx\RocXolid\DevKit\Repositories\CommandRepository;
use Softworx\RocXolid\DevKit\Components\Commander\Dashboard\Main as Dashboard;
use Softworx\RocXolid\DevKit\Components\Commander\Command\Tab as CommandTab;
use Softworx\RocXolid\DevKit\Components\Commander\Command\Form as CommandForm;
use Softworx\RocXolid\DevKit\Console\Contracts\Executor;
use Softworx\RocXolid\DevKit\Components\General\Message;
// rocXolid utils
use Softworx\RocXolid\Http\Responses\Contracts\AjaxResponse;

class CommanderController extends AbstractController
{
    protected $command_repository;

    protected $executor;

    protected $response;

    protected $command;

    public function __construct(CommandRepository $command_repository, Executor $executor, AjaxResponse $response)
    {
        $this->command_repository = $command_repository;
        $this->executor = $executor;
        $this->response = $response;
    }

    public function index(Request $request)
    {
        $assignments = $this->getViewAssignments($request);

        return (new Dashboard($this))->render('default', $assignments);
    }

    public function run(Request $request, $command_name, $arguments = [])
    {
        try
        {
            $command = $this->command_repository->getTaggedCommandByName(config('rocXolid.devkit.command-binding-tag'), $command_name);

            if (is_null($command))
            {
                throw new RuntimeException(sprintf('Command class for [%s] not found', $command_name));
            }

            if (method_exists($command, 'getRequestArguments'))
            {
                $request_arguments = $command->getRequestArguments($request, $arguments);
            }
            else
            {
                $request_arguments = $arguments;
            }

            if ($request->isMethod('post'))
            {
                $command->getForm()->submit();

                if ($command->getForm()->isValid())
                {
                    $output = $this->executor->execute($command->getName(), $request_arguments)->getOutput();
                }
                else
                {
                    return $this->error($request, $command);
                }
            }
            else
            {
                $output = $this->executor->execute($command->getName(), $request_arguments)->getOutput();
            }
        }
        catch (RuntimeException $e)
        {
            return $this->exception($request, $command, $e);
        }

        return $this->success($request, $command, $output);
    }

    public function help(Request $request, $command_name)
    {
        return $this->run($request, $command_name, [
            '--help'
        ]);
    }

    protected function success(Request $request, Command $command, $output)
    {
        $assignments = $this->getViewAssignments($request, $command, $output);

        if ($request->ajax())
        {
            $tab = (new CommandTab($command));

            return $this->response
                ->replace($tab->makeDomId($command->getName(), 'output'), (new Message())->fetch('command.output', $assignments + [ 'tab' => $tab ]))
                ->empty($tab->makeDomId($command->getName(), 'error'))
                ->get();
        }
        else
        {
            return (new Dashboard($this))->render('default', $assignments);
        }
    }

    protected function error(Request $request, Command $command)
    {
        $assignments = $this->getViewAssignments($request, $command);

        if ($request->ajax())
        {
            $tab = (new CommandTab($command));

            return $this->response
                ->replace($command->getFormComponent()->getDomId(), $command->getFormComponent()->fetch())
                ->replace($tab->makeDomId($command->getName(), 'error'), (new Message())->fetch('command.error', $assignments + [ 'tab' => $tab ]))
                ->empty($tab->makeDomId($command->getName(), 'output'))
                ->get();
        }
        else
        {
            return redirect()
                ->to(sprintf('%s#tab-%s', app('url')->previous(), md5($command->getName())))
                //->withErrors($command->getForm()->getErrors())
                ->with($command->getForm()->getSessionParam('errors'), $command->getForm()->getErrors())
                //->withInput();
                ->with($command->getForm()->getSessionParam('input'), $request->input());
        }
    }

    protected function exception(Request $request, Command $command, RuntimeException $e)
    {
        $assignments = $this->getViewAssignments($request, $command, null, $e);

        if ($request->ajax())
        {
            $tab = (new CommandTab($command));

            return $this->response
                ->replace($tab->makeDomId($command->getName(), 'error'), (new Message())->fetch('command.error', $assignments + [ 'tab' => $tab ]))
                ->empty($tab->makeDomId($command->getName(), 'output'))
                ->get();
        }
        else
        {
            return (new Dashboard($this))->render('default', $assignments);
        }
    }

    protected function getViewAssignments(Request $request, Command $command = null, $output = null, RuntimeException $e = null)
    {
        if ($request->ajax())
        {
            $assignments = [
                'command' => !is_null($command) ? $command->getName() : $command,
            ];
        }
        else
        {
            $assignments = [
                'command_tabs' => $this->command_repository->getTaggedCommandsTabs(config('rocXolid.devkit.command-binding-tag')),
                'active' => !is_null($command) ? $command->getName() : $command,
            ];
        }

        if (!is_null($output))
        {
            $assignments['output'] = [
                $command->getName() => $output,
            ];
        }

        if (!is_null($e))
        {
            $assignments['error'] = [
                $command->getName() => $e->getMessage() . ' ' . get_class($e),
            ];
        }

        return $assignments;
    }
}