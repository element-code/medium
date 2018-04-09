<?php
/**
 * @license https://creativecommons.org/licenses/by-sa/3.0/de/ CC BY-SA
 * @author Wolf Wortmann <ww@elementcode.de>
 *
 * @var array $la_data
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$la_data[ 'title' ]?></title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 50px;
            font-size: 16px;
            line-height: 20px;
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
            color: #333333;
        }

        #ContentArea {
            background-color: #FFFFFF;
            border-radius: 2px;
            padding: 30px 50px;
            max-width: 1500px;
            margin: 0 auto;
        }

        h1 {
            font-weight: 100;
            font-size: 34px;
            line-height: 45px;
        }

        #ContentArea > ul {
            margin: 70px 0 0;
            padding: 0;
        }

        ul > li {
            margin: 20px 0 0;
            padding: 20px 30px;
            display: block;
            list-style-type: none;
            background-color: #EAEAEA;
            border-radius: 3px;
        }

        ul > li:first-child {
            background-color: #333333;
            color: #FFFFFF;
            margin-top: 0;
        }

        ul > li:first-child * {
            color: #FFFFFF;
        }

        th {
            text-align: left;
            vertical-align: top;
        }

        td {
            padding: 0 20px;
            font-family: monospace;
        }

        pre {
            white-space: pre-wrap;
        }

        ol {
            padding: 0 0 0 30px;
        }

        ol > li {
            margin: 5px 0 0;
        }

        ol > li:first-child {
            margin-top: 0;
        }

        ol > li > pre {
            margin: 0;
        }
    </style>
</head>
<body>
<main id="ContentArea">
    <h1><?=$la_data[ 'title' ]?></h1>
    <ul>
		<? foreach ($la_data[ 'trace' ] as $la_trace) : ?>
            <li>
                <table>
					<? if ( !empty($la_trace[ 'message' ])): ?>
                        <tr>
                            <th>Fehler</th>
                            <td><?=$la_trace[ 'message' ]?></td>
                        </tr>
					<? endif; ?>
                    <tr>
                        <th>Ort</th>
                        <td><?=str_replace(APPPATH, '', $la_trace[ 'file' ])?>:<?=$la_trace[ 'line' ]?></td>
                    </tr>
					<? if ( !empty($la_trace[ 'function' ])): ?>
                        <tr>
                            <th>Funktion</th>
                            <td><?=( !empty($la_trace[ 'class' ]) ? str_replace(APPNAMESPACE, '', $la_trace[ 'class' ])
									: '' )?><?=( !empty($la_trace[ 'type' ]) ? $la_trace[ 'type' ]
									: '' )?><?=$la_trace[ 'function' ]?>()
                            </td>
                        </tr>
					<? endif; ?>
					<? if (DEBUG_MODE && !empty($la_trace[ 'args' ])): ?>
                        <tr>
                            <th>Parameter</th>
                            <td>
                                <ol>
									<? foreach ($la_trace[ 'args' ] as $ls_argument_content): ?>
                                        <li><? \Elementcode\Medium\Traits\Debugger::debug($ls_argument_content) ?></li>
									<? endforeach; ?>
                                </ol>
                            </td>
                        </tr>
					<? endif; ?>
                </table>
            </li>
		<? endforeach; ?>
    </ul>
</main>
</body>
</html>