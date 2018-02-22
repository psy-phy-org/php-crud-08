<?php

class Validation
{
    public function __construct()
    {
    }

    /**
     * チェックの実施
     */
    public function check($parameters, $conditions)
    {
        $errors = array();

        foreach ($conditions as $key => $condition) {
            // 評価する値の抽出
            $value = "";
            if (isset($parameters[$key])) {
                $value = $parameters[$key];
            }

            // チェック条件の初期化
            $max = (isset($condition["max"]) ? $condition["max"] : null);
            $min = (isset($condition["min"]) ? $condition["min"] : null);
            $type = (isset($condition["type"]) ? $condition["type"] : null);
            $disallowWhitespace = (isset($condition["disallowWhitespace"]) ? $condition["disallowWhitespace"] : null);
            $disallowZenkaku = (isset($condition["disallowZenkaku"]) ? $condition["disallowZenkaku"] : null);
            $required = (isset($condition["required"]) ? $condition["required"] : null);

            // 必須チェック
            if (!$this->checkRequired($value, $required)) {
                $errors[$key]["required"] = "必ず入力してください";
                // continue;
            }

            // max文字数チェック
            if (!$this->checkMax($value, $max)) {
                $errors[$key]["max"] = "{$max}文字以下で入力してください";
                // continue;
            }

            // min文字数チェック
            if (!$this->checkMin($value, $min)) {
                $errors[$key]["min"] = "{$min}文字以上で入力してください";
                // continue;
            }

            // 全角文字チェック
            if (!$this->checkZenkaku($value, $disallowZenkaku)) {
                $errors[$key]["disallowZenkaku"] = "全角文字を含めず入力してください";
                // continue;
            }

            // 空白文字チェック
            if (!$this->checkWhitespace($value, $disallowWhitespace)) {
                $errors[$key]["disallowWhitespace"] = "スペースを含めず入力してください";
                // continue;
            }

            // 形式チェック
            if (!$this->checkType($value, $type)) {
                $errors[$key]["type"] = "正しい形式で入力してください";
                // continue;
            }
        }

        if (count($errors) > 0) {
            return $errors;
        } else {
            return false;
        }
    }

    /**
     * 必須チェック
     */
    protected function checkRequired($value, $required)
    {
        if ($value == "") {
            if ($required) {
                return false;
            }
        }
        return true;
    }

    /**
     * max文字数チェック
     */
    protected function checkMax($value, $max)
    {
        if ($value == "") {
            return true;
        }
        if ($max) {
            $mb_length = mb_strlen($value, "UTF-8");
            if ($max < $mb_length) {
                return false;
            }
        }
        return true;
    }

    /**
     * min文字数チェック
     */
    protected function checkMin($value, $min)
    {
        if ($value == "") {
            return true;
        }
        if ($min) {
            $mb_length = mb_strlen($value, "UTF-8");
            if ($min > $mb_length) {
                return false;
            }
        }
        return true;
    }

    /**
     * 全角文字チェック
     */
    protected function checkZenkaku($value, $disallowZenkaku)
    {
        if ($value == "") {
            return true;
        }
        if ($disallowZenkaku) {
            $mb_length = mb_strlen($value, "UTF-8");
            if ($mb_length != strlen($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 空白文字チェック
     */
    protected function checkWhitespace($value, $disallowWhitespace)
    {
        if ($value == "") {
            return true;
        }
        if ($disallowWhitespace) {
            if (strpos($value, " ") !== false || strpos($value, "　") !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * 形式チェック
     */
    protected function checkType($value, $type)
    {
        $error = false;
        switch ($type) {
            // アルファベット
            case "alphabet":
            case "a":
                if (!preg_match('/^[a-z]*$/i', $value)) {
                    $error = true;
                }
                break;
            // 数字
            case "numeric":
            case "n":
                if (!preg_match('/^[0-9]*$/i', $value)) {
                    $error = true;
                }
                break;
            // アルファベットと数字
            case "alphabet&numeric":
            case "an":
                if (!preg_match('/^[a-z0-9]*$/i', $value)) {
                    $error = true;
                }
                break;
            // アルファベットと数字と記号
            case "alphabet&numeric&symbols":
            case "ans":
                if (!preg_match('/^[a-z0-9\!\"\#\$\%\&\'\(\)\=\-\^\~\`\@\[\{\+\;\*\:\]\}\,\<\.\>\/\?\_]*$/i', $value)) {
                    $error = true;
                }
                break;
            // Eメール（Eメール形式の正規表現判定については諸説あるため、各自調査の上利用してください）
            case "email":
                if (!preg_match('/^([a-z0-9\+_\-\.\(\)\^\=\*\&\%\#\!]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i', $value)) {
                    $error = true;
                }
                break;
            // URL
            case "url":
                if (!preg_match('/^https?\:\/\/[a-z0-9\/\:\%\#\$&\?\(\)~\.\=\+\-]+$/i', $value)) {
                    $error = true;
                }
                break;
            // 電話番号
            case "tel":
                if (!preg_match('/^[0-9\-\+]*$/i', $value)) {
                    $error = true;
                }
                break;
            // その他
            default:
                break;
        }
        return !$error;
    }
}
