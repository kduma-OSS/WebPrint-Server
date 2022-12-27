<?php

namespace App\Actions\Servers;

class GenerateServerTokenDockerCommandAction
{
    public function __construct(
        protected GenerateServerTokenEnvironmentalVariablesAction $generateServerTokenEnvironmentalVariablesAction
    ) {
    }

    public function handle(string $token)
    {
        $envVariables = $this->generateServerTokenEnvironmentalVariablesAction->handle($token);

        $commands = [];
        $commands[] = 'docker run --detach --restart always \\';
        $commands[] = '--volume $(pwd):/tmp/webprint-service-debug-output \\';
        $commands[] = sprintf('--env WEBPRINT_SERVER_ENDPOINT="%s" \\', $envVariables['WEBPRINT_SERVER_ENDPOINT']);
        $commands[] = sprintf('--env WEBPRINT_SERVICE_KEY="%s" \\', $envVariables['WEBPRINT_SERVICE_KEY']);
        $commands[] = '--env CUPS_SERVER=cups:631 \\';
        $commands[] = '--hostname webprint-service \\';
        $commands[] = '--name webprint-service \\';
        $commands[] = 'ghcr.io/kduma-oss/webprint-service:v2;';

        return implode("\n", $commands);
    }
}
