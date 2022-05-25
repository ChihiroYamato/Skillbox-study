<?php

define('PROJECT_ROOT_PATH', __DIR__);
define('PROJECT_SERVER_PATH', '/php-developer-base/Module-20');

session_start();

require_once PROJECT_ROOT_PATH . '/User.php';

if (! empty($_POST['METHOD'])) {
    $_SESSION['POST'] = $_POST;
    header_remove();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    return 0;
}

$user = new Base\User();

if (! empty($_SESSION['POST'])) {
    switch ($_SESSION['POST']['METHOD']) {
        case 'insert':
            $requsetDB = [];
            foreach (Base\User::USER_TABLE_COLUMNS as $column) {
                if (empty($_SESSION['POST'][$column])) {
                    throw new \Exception('Incorect data for insert method');
                }
                $requsetDB[$column] = $_SESSION['POST'][$column];
            }
            $requsetDB['date_created'] = (new \DateTime())->format('Y-m-d H:i:s');

            if (! $user->create($requsetDB)) {
                throw new \Exception('Error with create new user');
            }
            break;
        case 'delete':
            if (!$user->delete((int) $_SESSION['POST']['id'])) {
                throw new \Exception("Error with delete user by id {$_SESSION['POST']['id']}");
            }
            break;
        case 'edit':
            $requsetDB = [];
            foreach (['id', ...Base\User::USER_TABLE_COLUMNS] as $column) {
                if (empty($_SESSION['POST'][$column])) {
                    throw new \Exception('Incorect data for update method');
                }
                $requsetDB[$column] = $_SESSION['POST'][$column];
            }

            if (! $user->update($requsetDB)) {
                throw new \Exception('Error with update user');
            }
            break;
    }
    unset($_SESSION['POST']);
}

/** Подключения хедера html, в конце скрипта подключается футер */
require_once PROJECT_ROOT_PATH . '/templates/header.php';
?>
    <div class="flex-block">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="table-header table-dark">
                    <?php foreach(Base\User::USER_TABLE_COLUMNS_SHOW as $column) :?>
                    <td><?=$column?></td>
                    <?php endforeach;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user->list() as $row) :?>
                    <tr class="form-edit">
                        <form class="needs-validation" action="./" method="POST" novalidate>
                            <input type="hidden" name="METHOD" value="edit">
                            <input type="hidden" name="id" value="<?=$row['id']?>">
                            <?php foreach ($row as $column => $value) :?>
                                <td>
                                    <div class="display-active"><?=$value?></div>
                                    <?php if (in_array($column, Base\User::USER_TABLE_COLUMNS)) :?>
                                        <input class="display-none form-control" type="text" name="<?=$column?>" value="<?=$value?>" required>
                                    <?php endif;?>
                                </td>
                            <?php endforeach;?>
                            <td>
                                <button class="button-tranperent button on-orange" edit>Edit</button>
                            </td>
                        </form>
                        <form action="./" method="POST">
                            <td>
                                <input type="hidden" name="METHOD" value="delete">
                                <input type="hidden" name="id" value="<?=$row['id']?>">
                                <button class="button-tranperent button on-red" type="submit">Del</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach;?>
                <form class="needs-validation" action="./" method="POST" novalidate>
                    <input type="hidden" name="METHOD" value="insert">
                    <tr>
                        <td></td>
                        <?php foreach (Base\User::USER_TABLE_COLUMNS as $column) :?>
                            <td>
                                <input class="input-<?=$column?> form-control" type="text" name="<?=$column?>" required>
                            </td>
                        <?php endforeach;?>
                        <td></td>
                        <td align="center" colspan="2">
                            <button class="button-tranperent button on-blue" type="submit">Add</button>
                        </td>
                    </tr>
                </form>
            </tbody>
        </table>
    </div>
<?php require_once PROJECT_ROOT_PATH . '/templates/footer.php';?>
