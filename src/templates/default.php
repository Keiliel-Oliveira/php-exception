<?php

function errorDataItemIsEmpty($data): bool
{
    if (!$data) {
        return true;
    }

    return false;
}

function showFunctionDataItem($data): bool
{
    if (!$data['class']) {
        return true;
    }

    return false;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocorreu um erro</title>
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            padding: 50px;
        }

        .exception-container {
            display: flex;
            flex-wrap: nowrap;
            flex-direction: column;
            width: 100%;
            background-color: #3c2173;
            color: #ffffff;
            padding: 25px;
            border-radius: 10px;
        }

        .exception-container .exception-header {
            width: 100%;
            text-align: center;
            font-size: 1.5rem;
            border-bottom: 1px solid #ffffff;
        }

        .exception-container .exception-content {
            width: 80%;
            margin: 0 auto;
            padding-top: 30px;
        }

        .exception-content .error-data {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .exception-content .error-data-item {
            font-size: 1.1rem;
        }

        .exception-content .error-data-key {
            font-weight: bold;
            margin-right: 5px;
        }

        .exception-content .error-data-value {
            font-weight: normal;
        }

        .exception-content .error-message {
            font-size: 1.1rem;
            font-weight: normal;
            margin-top: 30px;
        }

        .exception-content .error-message>span {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            font-size: 1.3rem;
        }

        .exception-content .error-message>p {
            margin: 0;
        }

        .exception-content .error-message .error-data {
            list-style-type: disc;
            list-style-position: inside;
        }

        .exception-content .error-code {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            font-size: 1rem;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

    <section class="container">
        <div class="exception-container">
            <header class="exception-header">
                <h3>Ocorreu um erro: <?php echo $errorData['error_type']; ?></h3>
            </header>
            <div class="exception-content">
                <ul class="error-data">
                    <?php
                    foreach ($errorData['error_data'] as $key => $value) {
                        if (!errorDataItemIsEmpty($value)) {
                            ?>
                            <li class="error-data-item">
                                <span class="error-data-key"><?php echo $key; ?>:</span>
                                <span class="error-data-value"><?php echo $value; ?></span>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <div class="error-message">
                    <span>Mensagem do erro:</span>
                    <p><?php echo $errorData['error_message']; ?></p>
                </div>

                <?php
                if (!empty($errorData['error_possible_causes'])) {
                    ?>

                    <div class="error-message">
                        <span>Possiveis causas do erro:</span>
                        <ul class="error-data">
                            <?php
                            foreach ($errorData['error_possible_causes'] as $key => $value) {
                                ?>
                                <li class="error-data-item">
                                    <span class="error-data-value"><?php echo $value; ?></span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>

                    <?php
                }
                ?>

                <div class="error-message">
                    <span>Trecho de codigo onde o erro ocorreu:</span>
                    <div class="error-code">
                        <pre>
                        <code>
                            <?php highlight_string($errorData['error_code_snippet']); ?>
                        </code>
                    </pre>

                    </div>
                </div>

                <?php
                if (false != $errorData['error_origin_code_snippet']) {
                    ?>
                    <div class="error-message">
                        <span>Trecho de codigo da origem do erro:</span>
                        <div class="error-code">
                            <pre>
                                <code>
                                    <?php highlight_string($errorData['error_origin_code_snippet']); ?>
                                </code>
                            </pre>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

    </section>
</body>

</html>