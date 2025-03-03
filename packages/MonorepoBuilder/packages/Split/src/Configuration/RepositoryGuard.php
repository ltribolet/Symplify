<?php declare(strict_types=1);

namespace Symplify\MonorepoBuilder\Split\Configuration;

use Nette\Utils\Strings;
use Symplify\MonorepoBuilder\Split\Exception\InvalidGitRepositoryException;
use Symplify\MonorepoBuilder\Split\Exception\InvalidRepositoryFormatException;
use Symplify\SmartFileSystem\FileSystemGuard;

final class RepositoryGuard
{
    /**
     * @var string
     */
    private const GIT_REPOSITORY_PATTERN = '#((git|ssh|http(s)?|file)|(git@[\w\.]+)|[\w]+)(:(//)?)([\w\.@\:/\-~]+)(\.git)?(/)?#';

    /**
     * @var FileSystemGuard
     */
    private $fileSystemGuard;

    public function __construct(FileSystemGuard $fileSystemGuard)
    {
        $this->fileSystemGuard = $fileSystemGuard;
    }

    public function ensureIsRepository(string $possibleRepository): void
    {
        // local repositorye
        if ($possibleRepository === '.git') {
            return;
        }

        if (Strings::match($possibleRepository, self::GIT_REPOSITORY_PATTERN)) {
            return;
        }

        throw new InvalidRepositoryFormatException(sprintf('"%s" is not format for repository', $possibleRepository));
    }

    public function ensureIsRepositoryDirectory(string $repositoryDirectory): void
    {
        $this->fileSystemGuard->ensureDirectoryExists($repositoryDirectory);

        if (! file_exists($repositoryDirectory . '/.git')) {
            throw new InvalidGitRepositoryException(sprintf(
                '.git was not found in "%s" directory',
                $repositoryDirectory
            ));
        }
    }
}
