<?php

namespace frontend\controllers;

use common\models\Direction;
use common\models\EduYear;
use common\models\EduYearForm;
use common\models\EduYearType;
use common\models\Telegram;
use common\models\TelegramDtm;
use common\models\TelegramOferta;
use common\models\TelegramPerevot;
use Yii;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;


/**
 * Site controller
 */
class IkBotController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionBot()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $telegram = Yii::$app->telegram;
        $text = $telegram->input->message->text;
        $receivedMessageId = $telegram->input->message->message_id;
        $username = $telegram->input->message->chat->username;
        $telegram_id = $telegram->input->message->chat->id;

        try {

            $user = Telegram::find()
                ->andWhere(['chat_id' => $telegram_id, 'is_deleted' => 0])
                ->one();
            $userOne = $user;

            if (!$user) {
                $newUser = new Telegram();
                $newUser->chat_id = $telegram_id;
                $newUser->username = $username;
                $newUser->step = 1;
                $newUser->is_deleted = 0;
                $newUser->language_id = 1;
                $newUser->save(false);
                $user = $newUser;
                $userOne = $newUser;
            }

            $step = $userOne->step;
            $lang_id = $userOne->language_id;

            //ortga knopka uchun
            if ($text == "🔙Назад" || $text == "🔙Orqaga" || $text == "🔙Back") {
                if ($userOne) {
                    if ($userOne->step < 3) {
                        $text = '/start';
                    } else {
                        if ($userOne->step < 6) {
                            $userOne->step = 3;
                            $userOne->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "🇺🇿\nTa'lim tilini tanlang.\n\n🇷🇺\nВыберите язык обучения",
                                'reply_markup' => self::getLanguages()
                            ]);
                        } elseif ($userOne->step < 15) {
                            $userOne->step = 6;
                            $userOne->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "🔘 *Qabul turini tanlang\\!*",
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'keyboard' => [
                                        [
                                            ['text' => self::getTranslateMessage("Qabul 2024", $lang_id)],
                                            ['text' => self::getTranslateMessage("O‘qishni ko‘chirish", $lang_id)],
                                        ],
                                        [
                                            ['text' => self::getTranslateMessage("UZBMB natija", $lang_id)],
                                        ],
                                        [
                                            ['text' => self::undoKeyboardUser($user)]
                                        ]
                                    ],
                                    'resize_keyboard' => true,
                                ])
                            ]);
                        }
                    }
                }
            }
            //ortga knopka uchun

            if ($text == '/start') {
                if ($step == 15) {
                    $mes = self::result($userOne);
                    if ($userOne->bot_status == 0) {
                        $userOne->bot_status = 1;
                        $userOne->save(false);
                        $second_chat_id = -1002213280546;
                        $telegram->sendMessage([
                            'chat_id' => $second_chat_id,
                            'text' => $mes,
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'remove_keyboard' => true
                            ])
                        ]);
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => $mes,
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'remove_keyboard' => true
                            ])
                        ]);
                    }
                } else {
                    $userOne->step = 2;
                    $userOne->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "🇺🇿 \n*SARBON UNIVERSITETI* onlayn ariza topshirish uchun telefon raqamingizni yuboring\\. \n\n🇷🇺\n*SARBON UNIVERSITETI* Отправьте свой номер телефона, чтобы подать заявку онлайн",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => json_encode([
                            'keyboard' => [[
                                [
                                    'text' => "☎️",
                                    'request_contact' => true,
                                ]
                            ]],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true,
                        ])
                    ]);
                }
            }

            if ($step == 2) {
                if (json_encode($telegram->input->message->contact) != "null") {
                    $contact = json_encode($telegram->input->message->contact);
                    $contact_new = json_decode($contact);
                    $phone = preg_replace('/[^0-9]/', '', $contact_new->phone_number);
                    $phoneKod = substr($phone, 0, 3);
                    if ($phoneKod != 998) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Arizani faqat UZB telefon raqamlari berishi mumkin\\. Aloqa uchun\\: 771292929*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }
                    $userOne->phone = "+" . $phone;
                    $userOne->step = 3;
                    $userOne->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "🇺🇿\nTa'lim tilini tanlang.\n\n🇷🇺\nВыберите язык обучения",
                        'reply_markup' => self::getLanguages()
                    ]);
                }
            }


            if ($step == 3) {
                if (self::getSelectLanguage($text)) {
                    $userOne->language_id = self::getSelectLanguage($text);
                    $userOne->step = 4;
                    $userOne->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "✍️ *Pasportingizngiz seriyasi va nomerini yozing\\!* \n\n💡_Masalan\\: AB1234567_",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }
            }


            if ($step == 4) {
                $seria = self::seria($text);
                if ($seria) {
                    $userOne->passport_serial = substr($text, 0, 2);
                    $userOne->passport_number = substr($text, 2, 9);
                    $userOne->step = 5;
                    $userOne->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "✍️ *Tug'ilgan sanangizni \\(yil\\-oy\\-sana formatida\\) yozing\\!* \n\n💡_Masalan\\: 2001\\-10\\-16_",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                } else {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "✍️ *Pasportingizngiz seriyasi va nomerini yozing\\!* \n\n💡_Masalan\\: AB1234567_",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }
            }


            if ($step == 5) {
                $date = self::date($text);
                if ($date) {
                    $userOne->birthday = date("Y-m-d", strtotime($text));
                    $userOne->step = 6;
                    $passport = self::passport($userOne);
                    if ($passport['is_ok']) {
                        $userOne = $passport['user'];
                        $userOne->save(false);
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "🔘 *Qabul turini tanlang\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        ['text' => self::getTranslateMessage("Qabul 2024", $lang_id)],
                                        ['text' => self::getTranslateMessage("O‘qishni ko‘chirish", $lang_id)],
                                    ],
                                    [
                                        ['text' => self::getTranslateMessage("UZBMB natija", $lang_id)],
                                    ],
                                    [
                                        ['text' => self::undoKeyboardUser($user)]
                                    ]
                                ],
                                'resize_keyboard' => true,
                            ])
                        ]);
                    } else {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Pasport seriyasi\\, raqami va tug'ilgan sana orqali pasport ma'lumoti topilmadi\\. Qaytadan urinib ko'ring\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }
                } else {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "✍️ *Tug'ilgan sanangizni \\(yil\\-oy\\-sana formatida\\) yozing\\!* \n\n💡_Masalan\\: 2001\\-10\\-16_",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }
            }


            if ($step == 6) {
                $type = self::getSelectEduType($text);
                if ($type['is_ok']) {
                    $eduType = $type['id'];
                    $eduYearType = EduYearType::findOne($eduType);
                    if ($eduYearType) {
                        $userOne->edu_year_type_id = $eduYearType->id;
                        $userOne->step = 7;
                        $userOne->save(false);
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "🔘 *Ta'lim shaklini tanlang\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        ['text' => self::getTranslateMessage("Kunduzgi", $lang_id)],
                                        ['text' => self::getTranslateMessage("Kechgi", $lang_id)],
                                        ['text' => self::getTranslateMessage("Sirtqi", $lang_id)],
                                    ],
                                    [
                                        ['text' => self::undoKeyboardUser($user)]
                                    ]
                                ],
                                'resize_keyboard' => true,
                            ])
                        ]);
                    } else {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Qabul turi mavjud emas\\. Aloqaga chiqing: +998945055250\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }
                } else {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "🔘 *Qabul turini tanlang\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [
                                    ['text' => self::getTranslateMessage("Qabul 2024", $lang_id)],
                                    ['text' => self::getTranslateMessage("O‘qishni ko‘chirish", $lang_id)],
                                ],
                                [
                                    ['text' => self::getTranslateMessage("UZBMB natija", $lang_id)],
                                ],
                                [
                                    ['text' => self::undoKeyboardUser($user)]
                                ]
                            ],
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
            }


            if ($step == 7) {
                $type = self::getSelectEduForm($text);
                if ($type['is_ok']) {
                    $eduForm = $type['id'];
                    $eduYearForm = EduYearForm::findOne($eduForm);
                    if ($eduYearForm) {
                        $userOne->edu_year_form_id = $eduYearForm->id;
                        $userOne->step = 8;
                        $userOne->save(false);
                        $directions = Direction::find()
                            ->where([
                                'edu_year_id' => 1,
                                'language_id' => $userOne->language_id,
                                'edu_year_form_id' => $userOne->edu_year_form_id,
                                'edu_year_type_id' => $userOne->edu_year_type_id,
                                'status' => 1,
                                'is_deleted' => 0
                            ])->all();

                        if (count($directions) > 0) {
                            $keyboard = [];
                            foreach ($directions as $dir) {
                                $name = ($userOne->language_id == 1) ? str_replace('.', '', $dir->code) . ' - ' . $dir->name_uz : str_replace('.', '', $dir->code) . ' - ' . $dir->name_ru;
                                $keyboard[] = [['text' => $name]];
                            }
                            $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "🔘 *Yo‘nalish tanlang\\!*",
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'keyboard' => $keyboard,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => true
                                ])
                            ]);
                        } else {
                            $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "⁉️⛔️ *Yo‘nalish mavjud emas\\!*",
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => self::undoKeyboard($lang_id)
                            ]);
                        }
                    }
                } else {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "🔘 *Ta'lim shaklini tanlang\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [
                                    ['text' => self::getTranslateMessage("Kunduzgi", $lang_id)],
                                    ['text' => self::getTranslateMessage("Kechgi", $lang_id)],
                                    ['text' => self::getTranslateMessage("Sirtqi", $lang_id)],
                                ],
                                [
                                    ['text' => self::undoKeyboardUser($user)]
                                ]
                            ],
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
            }


            if ($step == 8) {
                $isDirection = self::getDirection($text, $userOne);
                if ($isDirection['is_ok']) {
                    $direction = $isDirection['direction'];
                    $userOne->direction_id = $direction->id;
                    $userOne->step = 9;
                    $userOne->save(false);

                    $oferta = TelegramOferta::findOne(['telegram_id' => $userOne->id]);
                    if ($oferta) {
                        if ($oferta->file != null) {
                            $fileName = \Yii::getAlias('@frontend/web/uploads/telegram/' . $userOne->id . '/' . $oferta->file);
                            if (file_exists($fileName)) {
                                unlink($fileName);
                            }
                        }
                        $oferta->delete();
                    }

                    $perevot = TelegramPerevot::findOne(['telegram_id' => $userOne->id]);
                    if ($perevot) {
                        if ($perevot->file != null) {
                            $fileName = \Yii::getAlias('@frontend/web/uploads/telegram/' . $userOne->id . '/' . $perevot->file);
                            if (file_exists($fileName)) {
                                unlink($fileName);
                            }
                        }
                        $perevot->delete();
                    }

                    $dtm = TelegramDtm::findOne(['telegram_id' => $userOne->id]);
                    if ($dtm) {
                        if ($dtm->file != null) {
                            $fileName = \Yii::getAlias('@frontend/web/uploads/telegram/' . $userOne->id . '/' . $dtm->file);
                            if (file_exists($fileName)) {
                                unlink($fileName);
                            }
                        }
                        $dtm->delete();
                    }

                    if ($direction->oferta == 1) {
                        $newOferta = new TelegramOferta();
                        $newOferta->telegram_id = $userOne->id;
                        $newOferta->save(false);
                    }
                    if ($userOne->eduYearType->edu_type_id == 2) {
                        $newPerevot = new TelegramPerevot();
                        $newPerevot->telegram_id = $userOne->id;
                        $newPerevot->save(false);
                    } elseif ($userOne->eduYearType->edu_type_id == 3) {
                        $newDtm = new TelegramDtm();
                        $newDtm->telegram_id = $userOne->id;
                        $newDtm->save(false);
                    }

                    if ($userOne->eduYearType->edu_type_id == 1) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "🔘 *Imtixon turini tanlang\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        ['text' => self::getTranslateMessage("Online", $lang_id)],
                                        ['text' => self::getTranslateMessage("Offline", $lang_id)],
                                    ],
                                    [
                                        ['text' => self::undoKeyboardUser($user)]
                                    ]
                                ],
                                'resize_keyboard' => true,
                            ])
                        ]);
                    } elseif ($userOne->eduYearType->edu_type_id == 2) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "📄 *Transkript fayl yuboring\\!* \n\n💡_Eslatma\\: Fayl faqat pdf formatda va 5mbdan oshmagan holatda yuborilishi shart\\!_",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    } elseif ($userOne->eduYearType->edu_type_id == 3) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "📄 *DTM fayl yuboring\\!* \n\n💡_Eslatma\\: Fayl faqat pdf formatda va 5mbdan oshmagan holatda yuborilishi shart\\!_",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }

                    $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "⁉️⛔️ *XATOLIK\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);

                } else {
                    $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "⁉️⛔️ *Bunday Yo‘nalish mavjud emas\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }
            }


            if ($step == 9) {
                if ($userOne->eduYearType->edu_type_id == 1) {
                    $isExam = self::getExamType($text);
                    if ($isExam['is_ok']) {
                        $userOne->exam_type = $isExam['id'];
                        $userOne->step = 15;
                        $userOne->save(false);
                        $oferta = TelegramOferta::findOne(['telegram_id' => $userOne->id]);
                        if ($oferta) {
                            $userOne->step = 10;
                            $userOne->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "📄 *5 yillik staj fayl yuboring\\!* \n\n💡_Eslatma\\: Fayl faqat pdf formatda va 5mbdan oshmagan holatda yuborilishi shart\\!_",
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => self::undoKeyboard($lang_id)
                            ]);
                        }

                        $mes = self::result($userOne);
                        if ($userOne->bot_status == 0) {
                            $userOne->bot_status = 1;
                            $userOne->save(false);
                            $second_chat_id = -1002213280546;
                            $telegram->sendMessage([
                                'chat_id' => $second_chat_id,
                                'text' => $mes,
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'remove_keyboard' => true
                                ])
                            ]);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => $mes,
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'remove_keyboard' => true
                                ])
                            ]);
                        }

                    } else {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Imtixon turi noto\\‘g‘ri yuborildi\\. Qaytadan yuboring\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'keyboard' => [
                                    [
                                        ['text' => self::getTranslateMessage("Online", $lang_id)],
                                        ['text' => self::getTranslateMessage("Offline", $lang_id)],
                                    ],
                                    [
                                        ['text' => self::undoKeyboardUser($user)]
                                    ]
                                ],
                                'resize_keyboard' => true,
                            ])
                        ]);
                    }
                } elseif ($userOne->eduYearType->edu_type_id == 2) {
                    $perevot = TelegramPerevot::findOne(['telegram_id' => $userOne->id]);
                    $document = json_encode($telegram->input->message->document);
                    $document_new = json_decode($document, true);

                    if ($document_new == null) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Fayl pdf formatda va 5mbdan oshmagan holatda yuklanishi shart\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }

                    $data = json_decode(file_get_contents("https://api.telegram.org/bot6686082454:AAHePqzPHAzvR5NMtpY6BfuwMnM3Cw9HKyI/getFile?file_id=" . $document_new['file_id']), false);
                    $url = "https://api.telegram.org/file/bot6686082454:AAHePqzPHAzvR5NMtpY6BfuwMnM3Cw9HKyI/" . $data->result->file_path;

                    $arr = (explode("documents/", $data->result->file_path));
                    $fileName = $arr[1];
                    $photoExten = (explode(".", $fileName));
                    $ext = $photoExten[1];
                    $fileSize = 1024 * 1024 * 5;

                    if ($ext != 'pdf') {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Fayl pdf formatda va 5mbdan oshmagan holatda yuklanishi shart\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }
                    if ($document_new['file_size'] > $fileSize) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *PDF fayl 5mb dan oshmasligi shart\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }

                    $name = "uploads/" . sha1($fileName) . time() . "." . $ext;
                    $perevot->file = $name;
                    $perevot->file_status = 1;
                    file_put_contents($name, fopen($url, 'r'));

                    if($perevot->save(false)) {
                        $userOne->step = 15;
                        $userOne->save(false);
                        $oferta = TelegramOferta::findOne(['telegram_id' => $userOne->id]);
                        if ($oferta) {
                            $userOne->step = 10;
                            $userOne->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "📄 *5 yillik staj fayl yuboring\\!* \n\n💡_Eslatma\\: Fayl faqat pdf formatda yuborilishi shart\\!_",
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => self::undoKeyboard($lang_id)
                            ]);
                        }

                        $mes = self::result($userOne);
                        if ($userOne->bot_status == 0) {
                            $userOne->bot_status = 1;
                            $userOne->save(false);
                            $second_chat_id = -1002213280546;
                            $telegram->sendMessage([
                                'chat_id' => $second_chat_id,
                                'text' => $mes,
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'remove_keyboard' => true
                                ])
                            ]);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => $mes,
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'remove_keyboard' => true
                                ])
                            ]);
                        }

                    } else {
                        return $telegram->sendMessage([
                            'chat_id' => 1841508935,
                            'text' => json_encode($perevot->errors)
                        ]);
                    }
                }  elseif ($userOne->eduYearType->edu_type_id == 3) {
                    $dtm = TelegramDtm::findOne(['telegram_id' => $userOne->id]);
                    $document = json_encode($telegram->input->message->document);
                    $document_new = json_decode($document, true);

                    if ($document_new == null) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Fayl pdf formatda va 5mbdan oshmagan holatda yuklanishi shart\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }

                    $data = json_decode(file_get_contents("https://api.telegram.org/bot6686082454:AAHePqzPHAzvR5NMtpY6BfuwMnM3Cw9HKyI/getFile?file_id=" . $document_new['file_id']), false);
                    $url = "https://api.telegram.org/file/bot6686082454:AAHePqzPHAzvR5NMtpY6BfuwMnM3Cw9HKyI/" . $data->result->file_path;

                    $arr = (explode("documents/", $data->result->file_path));
                    $fileName = $arr[1];
                    $photoExten = (explode(".", $fileName));
                    $ext = $photoExten[1];
                    $fileSize = 1024 * 1024 * 5;

                    if ($ext != 'pdf') {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *Fayl pdf formatda va 5mbdan oshmagan holatda yuklanishi shart\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }
                    if ($document_new['file_size'] > $fileSize) {
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => "⁉️⛔️ *PDF fayl 5mb dan oshmasligi shart\\!*",
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => self::undoKeyboard($lang_id)
                        ]);
                    }

                    $name = "uploads/" . sha1($fileName) . time() . "." . $ext;
                    $dtm->file = $name;
                    $dtm->file_status = 1;
                    file_put_contents($name, fopen($url, 'r'));

                    if($dtm->save(false)) {
                        $userOne->step = 15;
                        $userOne->save(false);
                        $oferta = TelegramOferta::findOne(['telegram_id' => $userOne->id]);
                        if ($oferta) {
                            $userOne->step = 10;
                            $userOne->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => "📄 *5 yillik staj fayl yuboring\\!* \n\n💡_Eslatma\\: Fayl faqat pdf formatda va 5mbdan oshmagan holatda yuborilishi shart\\!_",
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => self::undoKeyboard($lang_id)
                            ]);
                        }

                        $mes = self::result($userOne);
                        if ($userOne->bot_status == 0) {
                            $userOne->bot_status = 1;
                            $userOne->save(false);
                            $second_chat_id = -1002213280546;
                            $telegram->sendMessage([
                                'chat_id' => $second_chat_id,
                                'text' => $mes,
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'remove_keyboard' => true
                                ])
                            ]);
                            return $telegram->sendMessage([
                                'chat_id' => $telegram_id,
                                'text' => $mes,
                                'parse_mode' => 'MarkdownV2',
                                'reply_markup' => json_encode([
                                    'remove_keyboard' => true
                                ])
                            ]);
                        }

                    } else {
                        return $telegram->sendMessage([
                            'chat_id' => 1841508935,
                            'text' => json_encode($dtm->errors)
                        ]);
                    }
                }
            }


            if ($step == 10) {
                $oferta = TelegramOferta::findOne(['telegram_id' => $userOne->id]);
                $document = json_encode($telegram->input->message->document);
                $document_new = json_decode($document, true);

                if ($document_new == null) {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "⁉️⛔️ *Fayl pdf formatda va 5mbdan oshmagan holatda yuklanishi shart\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }

                $data = json_decode(file_get_contents("https://api.telegram.org/bot6686082454:AAHePqzPHAzvR5NMtpY6BfuwMnM3Cw9HKyI/getFile?file_id=" . $document_new['file_id']), false);
                $url = "https://api.telegram.org/file/bot6686082454:AAHePqzPHAzvR5NMtpY6BfuwMnM3Cw9HKyI/" . $data->result->file_path;

                $arr = (explode("documents/", $data->result->file_path));
                $fileName = $arr[1];
                $photoExten = (explode(".", $fileName));
                $ext = $photoExten[1];
                $fileSize = 1024 * 1024 * 5;

                if ($ext != 'pdf') {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "⁉️⛔️ *Fayl pdf formatda va 5mbdan oshmagan holatda yuklanishi shart\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }
                if ($document_new['file_size'] > $fileSize) {
                    return $telegram->sendMessage([
                        'chat_id' => $telegram_id,
                        'text' => "⁉️⛔️ *PDF fayl 5mb dan oshmasligi shart\\!*",
                        'parse_mode' => 'MarkdownV2',
                        'reply_markup' => self::undoKeyboard($lang_id)
                    ]);
                }

                $name = "uploads/" . sha1($fileName) . time() . "." . $ext;
                $oferta->file = $name;
                $oferta->file_status = 1;
                file_put_contents($name, fopen($url, 'r'));

                if($oferta->save(false)) {
                    $userOne->step = 15;
                    $userOne->save(false);

                    $mes = self::result($userOne);
                    if ($userOne->bot_status == 0) {
                        $userOne->bot_status = 1;
                        $userOne->save(false);
                        $second_chat_id = -1002213280546;
                        $telegram->sendMessage([
                            'chat_id' => $second_chat_id,
                            'text' => $mes,
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'remove_keyboard' => true
                            ])
                        ]);
                        return $telegram->sendMessage([
                            'chat_id' => $telegram_id,
                            'text' => $mes,
                            'parse_mode' => 'MarkdownV2',
                            'reply_markup' => json_encode([
                                'remove_keyboard' => true
                            ])
                        ]);
                    }
                } else {
                    return $telegram->sendMessage([
                        'chat_id' => 1841508935,
                        'text' => json_encode($oferta->errors)
                    ]);
                }
            }


        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => 1841508935,
                'text' => $e->getMessage(),
            ]);
        } catch (\Throwable $t) {
            return $telegram->sendMessage([
                'chat_id' => 1841508935,
                'text' => $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(),
            ]);
        }
    }

    public static function result($userOne)
    {

        if ($userOne->confirm_date == null) {
            $userOne->confirm_date = date("Y-m-d H:i:s");
            $userOne->save(false);
        }

        $gender = ($userOne->gender == 0) ? '👩‍🎓' : '👨‍🎓';

        $ariza = "📤  *Arizangiz muvaffaqiyatli yuborildi\\.* \n\n";
        $full_name = $gender . " *F\\.I\\.O\\:* " . self::escapeMarkdownV2($userOne->last_name . ' ' . $userOne->first_name . ' ' . $userOne->middle_name) . "\n";
        $pass = "📑 *Pasport ma\\'lumoti\\:* " . self::escapeMarkdownV2($userOne->passport_serial . ' ' . $userOne->passport_number) . "\n";
        $birthday = "🗓 *Tug\\'ilgan sana\\:* " . self::escapeMarkdownV2($userOne->birthday) . "\n";
        $phone = "📞 *Telefon raqam\\:* ".self::escapeMarkdownV2($userOne->phone)."\n";

        $hr = "\\- \\- \\- \\- \\- \\- \\- \\- \\- \n";

        if ($userOne->eduYearType->edu_type_id == 1) {
            $examType = 'Online';
            if ($userOne->exam_type == 1) {
                $examType = 'Offline';
            }
        } else {
            $examType = "\\- \\- \\- \\- \\-";
        }

        $admin = "\n\n📌 _Arizangiz ko\\'rib chiqilib tez orada siz bilan 👩‍💻 operatorlarimiz bog\\'lanishadi\\. Aloqa uchun\\: 771292929_";
        $sendSmsDate = "\n\n🕒 ️ _Yuborilgan vaqt\\: ". self::escapeMarkdownV2($userOne->confirm_date) ."_";

        $direc = $userOne->direction;

        $d = "🔘 *Yo\\'nalish\\:* " . self::escapeMarkdownV2($direc->name_uz) . "\n";
        $code = "🔸 *Yo\\'nalish kodi\\:* " . self::escapeMarkdownV2(str_replace('.', '', $direc->code)) . "\n";
        $edTy = "♦️ *Qabul turi\\:* " . self::escapeMarkdownV2($direc->eduType->name_uz) . "\n";
        $edFo = "🔹 *Ta\\'lim shakli\\:* " . self::escapeMarkdownV2($direc->eduForm->name_uz) . "\n";
        $im_type = "▫️ *Imtixon turi\\:* ".$examType."\n";
        $edLa = "🇺🇿 *Ta\\'lim tili\\:* ". self::escapeMarkdownV2($direc->language->name_uz);
        $mes = $ariza . $full_name . $pass . $birthday. $phone . $hr . $d . $code . $edTy . $edFo . $im_type . $edLa. $admin . $sendSmsDate;

        return $mes;
    }

    private static function escapeMarkdownV2($text)
    {
        $escape_chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        $escaped_text = str_replace($escape_chars, array_map(function($char) {
            return '\\' . $char;
        }, $escape_chars), $text);

        return $escaped_text;
    }


    public static function getDirection($name, $userOne)
    {
        $directions = Direction::find()
            ->where([
                'edu_year_id' => 1,
                'language_id' => $userOne->language_id,
                'edu_year_form_id' => $userOne->edu_year_form_id,
                'edu_year_type_id' => $userOne->edu_year_type_id,
                'status' => 1,
                'is_deleted' => 0
            ])->all();
        if (count($directions) > 0) {
            foreach ($directions as $dir) {
                $dir_name = ($userOne->language_id == 1) ? str_replace('.', '', $dir->code) . ' - ' . $dir->name_uz : str_replace('.', '', $dir->code) . ' - ' . $dir->name_ru;
                if ($dir_name == $name) {
                    return ['is_ok' => true, 'direction' => $dir];
                }
            }
        }
        return ['is_ok' => false];
    }

    public static function seria($text)
    {
        $pattern = '/^[A-Z]{2}\d{7}$/';
        if (preg_match($pattern, $text)) {
            return true;
        } else {
            return false;
        }
    }

    public static function date($text)
    {
        $format = 'Y-m-d';
        $d = \DateTime::createFromFormat($format, $text);
        return $d && $d->format($format) === $text;
    }

    public static function passport($user)
    {
        $client = new Client();
        $url = 'https://api.online-mahalla.uz/api/v1/public/tax/passport';
        $params = [
            'series' => $user->passport_serial,
            'number' => $user->passport_number,
            'birth_date' => $user->birthday,
        ];
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData($params)
            ->send();

        if ($response->isOk) {
            $responseData = $response->data;
            $passport = $responseData['data']['info']['data'];
            $user->first_name = $passport['name'];
            $user->last_name = $passport['sur_name'];
            $user->middle_name = $passport['patronymic_name'];
            $user->passport_pin = (string)$passport['pinfl'];

            $user->passport_issued_date = date("Y-m-d", strtotime($passport['expiration_date']));
            $user->passport_given_date = date("Y-m-d", strtotime($passport['given_date']));
            $user->passport_given_by = $passport['given_place'];
            $user->gender = $passport['gender'];
            return ['is_ok' => true, 'user' => $user];
        }
        return ['is_ok' => false];
    }


    public static function undoKeyboard($lang_id)
    {
        if ($lang_id == 3) {
            $text_keybord_undo = "🔙Back";
        } elseif ($lang_id == 2) {
            $text_keybord_undo = "🔙Назад";
        } else {
            $text_keybord_undo = "🔙Orqaga";
        }
        $keyboard_basic_undo = json_encode([
            'keyboard' => [
                [
                    ['text' => $text_keybord_undo]
                ]
            ], 'resize_keyboard' => true
        ]);
        return $keyboard_basic_undo;
    }

    public static function undoKeyboardUser($user)
    {
        if ($user->lang_id == 3) {
            $text_keybord_undo = "🔙Back";
        } elseif ($user->lang_id == 2) {
            $text_keybord_undo = "🔙Back";
        } else {
            $text_keybord_undo = "🔙Orqaga";
        }
        return $text_keybord_undo;
    }

    public static function getLanguages()
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => "🇺🇿O‘zbek🇺🇿"],
                    ['text' => "🇷🇺Русский🇷🇺"],
                ],
            ], 'resize_keyboard' => true
        ]);
    }

    public static function getEduType()
    {
        return json_encode([
            'keyboard' => [
                [
                    ['text' => "Qabul 2024"],
                    ['text' => "O‘qishni ko‘chirish"],
                ],
                [
                    ['text' => "🇬🇧󠁧󠁢󠁥󠁮󠁧󠁿English🇬🇧󠁧󠁢󠁥󠁮󠁧󠁿"],
                ]
            ], 'resize_keyboard' => true
        ]);
    }

    public static function getSelectEduType($type)
    {
        if ($type == 'Qabul 2024' || $type == 'Прием 2024 г.') {
            return ['is_ok' => true, 'id' => 1];
        }
        if ($type == 'O‘qishni ko‘chirish' || $type == 'Перевод') {
            return ['is_ok' => true, 'id' => 2];
        }
        if ($type == 'UZBMB natija' || $type == 'Результат УЗБМБ') {
            return ['is_ok' => true, 'id' => 3];
        }
        return ['is_ok' => false];
    }

    public static function getExamType($type)
    {
        if ($type == 'Online') {
            return ['is_ok' => true, 'id' => 0];
        }
        if ($type == 'Offline') {
            return ['is_ok' => true, 'id' => 1];
        }
        return ['is_ok' => false];
    }

    public static function getSelectEduForm($type)
    {
        if ($type == 'Kunduzgi' || $type == 'Очное') {
            return ['is_ok' => true, 'id' => 1];
        }
        if ($type == 'Sirtqi' || $type == 'Заучный') {
            return ['is_ok' => true, 'id' => 2];
        }
        if ($type == 'Kechgi' || $type == 'Вечер') {
            return ['is_ok' => true, 'id' => 3];
        }
        return ['is_ok' => false];
    }

    public static function getSelectLanguage($lang)
    {
        if (($lang == '🇺🇿O‘zbek🇺🇿')) {
            return 1;
        }
        if (($lang == '🇷🇺Русский🇷🇺')) {
            return 3;
        }
        return false;
    }

    public static function getSelectLanguageText($lang)
    {
        $array = [
            1 => "uz",
            3 => "ru",
        ];
        return isset($array[$lang]) ? $array[$lang] : null;
    }

    public static function getTranslateMessage($text, $lang_id)
    {
        $lang = self::getSelectLanguageText($lang_id);
        $array = [
            "Qo'shimcha izox qoldiring..." => [
                "uz" => "Qo'shimcha izox qoldiring...",
                "ru" => "Оставить комментарий...",
                "en" => "Leave a comment...",
            ],
            'IELTS nechida' => [
                "uz" => "IELTS nechida?",
                "ru" => "Сколько стоит IELTS?",
                "en" => "Your IELTS score?",
            ],
            'Siz bakalavrgami yoki magistrgami?' => [
                "uz" => "Siz bakalavrgami yoki magistrgami?",
                "ru" => "Вы бакалавр или магистр?",
                "en" => "Are you a bachelor or master?",
            ],
            "Xato format" =>
                [
                    "uz" => "Xato format",
                    "ru" => "Формат ошибки",
                    "en" => "Error format",
                ],
            "Qabul 2024" =>
                [
                    "uz" => "Qabul 2024",
                    "ru" => "Прием 2024 г.",
                    "en" => "Admission 2024",
                ],
            "O‘qishni ko‘chirish" =>
                [
                    "uz" => "O‘qishni ko‘chirish",
                    "ru" => "Перевод",
                    "en" => "Transfer of study",
                ],
            "Qabul turini tanlang..." =>
                [
                    "uz" => "Qabul turini tanlang...",
                    "ru" => "Выберите тип приема...",
                    "en" => "Select the type of reception...",
                ],
            "UZBMB natija" =>
                [
                    "uz" => "UZBMB natija",
                    "ru" => "Результат УЗБМБ",
                    "en" => "UZBMB result",
                ],
            "Kunduzgi" =>
                [
                    "uz" => "Kunduzgi",
                    "ru" => "Очное",
                ],
            "Kechgi" =>
                [
                    "uz" => "Kechgi",
                    "ru" => "Вечер",
                ],
            "Sirtqi" =>
                [
                    "uz" => "Sirtqi",
                    "ru" => "Заучный",
                ],
            "📞 Telefon raqamingizni yuboring 📞" =>
                [
                    "uz" => "📞Telefon raqamingizni yuboring📞",
                    "ru" => "📞Отправьте свой номер телефона📞",
                    "en" => "📞Send your phone number📞",
                ],
            'Yoshiz nechida' => [
                "uz" => "Yoshiz nechida?",
                "ru" => "Сколько тебе лет?",
                "en" => "How old are you?",
            ],
            "Tasdiqlash kodini kiriting..." =>
                [
                    "uz" => "Tasdiqlash kodini kiriting...",
                    "ru" => "Введите код подтверждения...",
                    "en" => "Enter confirmation code...",
                ],
            "Tasdiqlash kodi noto'g'ri. Iltimos tasdiqlash kodini qayta kiriting." =>
                [
                    "uz" => "Tasdiqlash kodi noto'g'ri. Iltimos tasdiqlash kodini qayta kiriting.",
                    "ru" => "Код подтверждения неверный. Пожалуйста, введите код подтверждения еще раз.",
                    "en" => "The confirmation code is incorrect. Please enter the confirmation code again.",
                ],
            "Qaysi viloyatda yashaysiz" =>
                [
                    "uz" => "Qaysi viloyatda yashaysiz?",
                    "ru" => "В какой области проживаете?",
                    "en" => "Which province do you live in?",
                ],
            "Qaysi tumanda yashaysiz?" =>
                [
                    "uz" => "Qaysi tumanda yashaysiz?",
                    "ru" => "В какой pайон проживаете?",
                    "en" => "Which district do you live in?",
                ],
            "Manzil qiymati 10 tadan ko'p bo'lsin..." =>
                [
                    "uz" => "Manzil qiymati 10 tadan ko'p bo'lsin...",
                    "ru" => "Пусть значение адреса больше 10...",
                    "en" => "Let the address value be more than 10 ...",
                ],
            "Tabriklaymiz, siz ro’yxatdan muvaffaqiyatli o’tdingiz Kerakli bo'limni tanlang!!!" =>
                [
                    "uz" => "Tabriklaymiz, siz ro’yxatdan muvaffaqiyatli o’tdingiz Kerakli bo'limni tanlang!!!",
                    "ru" => "Поздравляем, вы успешно зарегистрировались. Выберите нужный раздел!!!",
                    "en" => "Congratulations, you have successfully registered. Select the desired section !!!",
                ],
            "Yashash manzilingizni yozing..." =>
                [
                    "uz" => "Yashash manzilingizni yozing...",
                    "ru" => "Введите свой адрес...",
                    "en" => "Enter your address...",
                ],
            //"" =>
//                [
//                    "uz" => "",
//                    "ru" => "",
//                    "en" => "",
//                ],
            //"" =>
//                [
//                    "uz" => "",
//                    "ru" => "",
//                    "en" => "",
//                ],


        ];
        if (isset($array[$text])) {
            return isset($array[$text][$lang]) ? $array[$text][$lang] : $text;
        } else {
            return $text;
        }
    }
}
