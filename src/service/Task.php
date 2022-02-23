<?php

namespace anatolev\service;

use anatolev\exception\SourceFileException;
use anatolev\exception\StatusNotExistException;
use anatolev\exception\ActionNotExistException;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_WORK = 'work';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    const STATUS_NEW_ID = 1;
    const STATUS_CANCEL_ID = 2;
    const STATUS_WORK_ID = 3;
    const STATUS_DONE_ID = 4;
    const STATUS_FAILED_ID = 5;

    private array $actions = [];

    public function __construct(
        private int $task_id,
        private int $customer_id,
        private ?int $executor_id,
        private string $status = self::STATUS_NEW
    ) {}

    /**
     * Возвращает "карту" статусов
     * [внутреннее имя => название статуса на русском]
     *
     * @return array
     */
    public function getStatusMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_WORK => 'В работе',
            self::STATUS_DONE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /**
     * Возвращает "карту" действий
     * [внутреннее имя => название действия на русском]
     *
     * @return array
     */
    public function getActionMap(): array
    {
        $actions = [ActCancel::class, ActRespond::class, ActDone::class, ActRefuse::class];
        $action_map = [];

        foreach ($actions as $action_key) {
            $action = $this->getAction($action_key);
            $action_map[$action->getInnerName()] = $action->getName();
        }

        return $action_map;
    }

    /**
     * Возвращает статус, в который перейдёт задание после выполнения
     * указанного действия
     *
     * @param string $action Действие (внутреннее имя)
     *
     * @throws ActionNotExistException
     *
     * @return string
     */
    public function getNextStatus(string $action): string
    {
        if (!array_key_exists($action, $this->getActionMap())) {
            throw new ActionNotExistException("Действие не существует");
        }

        $array = [
            $this->getAction(ActCancel::class)->getInnerName() => self::STATUS_CANCEL,
            $this->getAction(ActRespond::class)->getInnerName() => self::STATUS_WORK,
            $this->getAction(ActDone::class)->getInnerName() => self::STATUS_DONE,
            $this->getAction(ActRefuse::class)->getInnerName() => self::STATUS_FAILED
        ];

        return $array[$action] ?? '';
    }

    /**
     * Возвращает массив доступных действий для указанного статуса
     * и пользователя
     *
     * @param int $user_id Идентификатор аутентифицированного пользователя
     *
     * @throws StatusNotExistException
     *
     * @return array
     */
    public function getAvailableActions(int $user_id): array
    {
        if (!array_key_exists($this->status, $this->getStatusMap())) {
            throw new StatusNotExistException("Статус не существует");
        }

        $array = [
            self::STATUS_NEW => [
                $this->getAction(ActCancel::class),
                $this->getAction(ActRespond::class)
            ],
            self::STATUS_WORK => [
                $this->getAction(ActDone::class),
                $this->getAction(ActRefuse::class)
            ]
        ];

        $available_actions = [];

        foreach ($array as $key => $actions) {

            foreach ($actions as $action) {
                $ids = [$this->task_id, $this->customer_id, $this->executor_id, $user_id];

                if ($this->status === $key && $action->checkUserRights(...$ids)) {
                    $available_actions[] = $action;
                }
            }
        }

        return $available_actions;
    }

    /**
     * Возвращает объект указанного действия
     *
     * @param string $action Действие
     *
     * @throws SourceFileException
     *
     * @return TaskAction
     */
    private function getAction(string $action): TaskAction
    {
        if (!class_exists($action)) {
            throw new SourceFileException("Класс действия не найден");
        }

        if (!isset($this->actions[$action])) {
            $this->actions[$action] = new $action();
        }

        return $this->actions[$action];
    }
}
