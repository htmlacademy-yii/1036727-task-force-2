<?php
namespace Anatolev\Service;

use Anatolev\Exception\ClassNotFoundException;
use Anatolev\Exception\StatusNotExistException;
use Anatolev\Exception\ActionNotExistException;

class Task
{
    private const STATUS_NEW = 'new';
    private const STATUS_CANCEL = 'cancel';
    private const STATUS_WORK = 'work';
    private const STATUS_DONE = 'done';
    private const STATUS_FAILED = 'failed';

    private array $actions = [];

    public function __construct(
        private int $executor_id,
        private int $customer_id,
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
        $actions = ['act_cancel', 'act_respond', 'act_done', 'act_refuse'];
        foreach ($actions as $action_key) {
            $action = $this->getAction($action_key);
            $action_map[$action->getInnerName()] = $action->getName();
        }

        return $action_map ?? [];
    }

    /**
     * Возвращает статус, в который перейдёт задание после выполнения
     * указанного действия
     *
     * @param string $action Действие (внутреннее имя)
     *
     * @return string
     */
    public function getNextStatus(string $action): string
    {
        if (!array_key_exists($action, $this->getActionMap())) {
            throw new ActionNotExistException("Действие не существует");
        }

        $data = [
            $this->getAction('act_cancel')->getInnerName() => self::STATUS_CANCEL,
            $this->getAction('act_respond')->getInnerName() => self::STATUS_WORK,
            $this->getAction('act_done')->getInnerName() => self::STATUS_DONE,
            $this->getAction('act_refuse')->getInnerName() => self::STATUS_FAILED
        ];

        return $data[$action] ?? '';
    }

    /**
     * Возвращает массив доступных действий для указанного статуса
     * и пользователя
     *
     * @param string $status Статус задания (внутреннее имя)
     * @param int $user_id Идентификатор пользователя
     *
     * @return array
     */
    public function getAvailableActions(string $status, int $user_id): array
    {
        if (!array_key_exists($status, $this->getStatusMap())) {
            throw new StatusNotExistException("Статус не существует");
        }

        $array = [
            self::STATUS_NEW => [
                $this->getAction('act_cancel'),
                $this->getAction('act_respond')
            ],
            self::STATUS_WORK => [
                $this->getAction('act_done'),
                $this->getAction('act_refuse')
            ]
        ];

        foreach ($array as $key => $actions) {
            foreach ($actions as $action) {
                $ids = [$this->executor_id, $this->customer_id, $user_id];
                if ($status === $key && $action->checkUserRights(...$ids)) {
                    $available_actions[] = $action;
                }
            }
        }

        return $available_actions ?? [];
    }

    /**
     * Возвращает объект указанного действия
     *
     * @param string $action Действие
     *
     * @return TaskAction
     */
    private function getAction(string $action): TaskAction
    {
        $actions = ['act_cancel', 'act_respond', 'act_done', 'act_refuse'];
        if (!in_array($action, $actions)) {
            throw new ActionNotExistException("Действие не существует");
        }

        if (!isset($this->actions[$action])) {
            $classname = '\Anatolev\Service\\' . str_replace('_', '', $action);
            if (!class_exists($classname)) {
                throw new ClassNotFoundException("Класс не найден");
            }
            $this->actions[$action] = new $classname();
        }

        return $this->actions[$action];
    }
}
