<?php
/**
 * This file is part of the "archinspec" project.
 *
 * (c) Fabian Keller <hello@fabian-keller.de>
 *
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace ArchInspec\Command;

interface CliMessage
{
    const VERSION = 'v0.0.2';

    const NAME = 'ArchInspec by Fabian Keller';

    const COMMAND = 'inspect';

    const HELP = 'Please visit <info>https://github.com/fakeller/archinspec</info> for detailed informations.';

    const ARGUMENT_CONFIG = 'Path to yaml configuration file.';

    const READ_CONFIG_FROM = 'Configuration read from ';
}
