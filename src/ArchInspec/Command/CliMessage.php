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
    // // COMMAND INFO // //

    const VERSION = 'v0.0.2';

    const NAME = 'ArchInspec by Fabian Keller';

    const COMMAND = 'inspect';

    const HELP = 'Please visit <info>https://github.com/fakeller/archinspec</info> for detailed informations.';

    const ARGUMENT_CONFIG = 'Path to yaml configuration file.';

    const OPTION_REPORT_UNDEFINED = 'Report architectural relationships that are not covered by any policy. Use this option to improve your architecture description file.';


    // // COMMAND PROGRESS // //

    const READ_CONFIG_FROM = '. Configuration read from ';

    const READ_ARCHITECTURE_FILE_FROM = '. Loading architecture definition: ';

    const STARTING_ANALYSIS = '. Starting Analysis ...';


    // // TROUBLESHOOTING AND USER GUIDE // //

    const ARCHITECTURE_FILE_NOT_READABLE = '<error>It seems that the architecture file \'%s\' exists, but is not readable by archinspec! Please make sure to set the correct permissions.</error>';

    const ARCHITECTURE_FILE_ASK_TO_CREATE = '<question>It seems that the architecture file \'%s\' doest not exist. Would you like me to create it for you? (y/N)</question> ';

    const ARCHITECTURE_FILE_CREATED_EMPTY = 'Created an empty architecture file in \'%s\'';

    const ARCHITECTURE_FILE_HOWTO = '<info>For more information on how to write an architecture file visit: https://github.com/FaKeller/archinspec/wiki/Architecture-Definitions</info>';

}
