<?php

declare(strict_types=1);

/**
 * This file is part of the PHPSubreg package
 *
 * https://github.com/Spoje-NET/php-subreg
 *
 * (c) Vítězslav Dvořák <http://spojenet.cz/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Subreg;

/**
 * Thrown when soap.subreg.cz returns status=error.
 *
 * Preserves major/minor error codes from the API so callers can branch
 * on specific failures (e.g. curExpDate mismatch, insufficient credit).
 */
class ApiException extends \RuntimeException
{
    public readonly int $major;

    public readonly int $minor;

    /**
     * @param array{errormsg?: string, errorcode?: array{major?: int|string, minor?: int|string}} $error
     */
    public function __construct(array $error, ?\Throwable $previous = null)
    {
        $this->major = (int) ($error['errorcode']['major'] ?? 0);
        $this->minor = (int) ($error['errorcode']['minor'] ?? 0);
        $message = (string) ($error['errormsg'] ?? 'Subreg API error');

        parent::__construct($message, $this->major, $previous);
    }

    /**
     * Full API error payload as returned by Subreg.
     *
     * @return array{errormsg: string, errorcode: array{major: int, minor: int}}
     */
    public function toErrorArray(): array
    {
        return [
            'errormsg' => $this->getMessage(),
            'errorcode' => [
                'major' => $this->major,
                'minor' => $this->minor,
            ],
        ];
    }
}
