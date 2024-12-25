<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

final class FileGenerator
{
    public function __construct(
        private Environment $twig,
        private readonly Filesystem $filesystem
    )
    {
    }

    public function generate(string $path, string $templatePath, array $variables): void
    {
        $template = $this->twig->load($templatePath);
        $content = $template->render($variables);

        $fileName = $path . $variables['fileName'] . '.php';

        if (!$this->filesystem->exists(dirname($fileName))) {
            $this->filesystem->mkdir(dirname($fileName));
        }

        $this->filesystem->dumpFile($fileName, $content);
    }
}