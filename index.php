<style>
    <?php include 'style.css'; ?>
</style>
<?php include 'components/header.php';

    class Game {
        public $board;
        public $turn;
        public $won;
        public function __construct()
        {
            $this->board = [
                ['', '', ''],
                ['', '', ''],
                ['', '', '']
            ];
            $this->turn = 'X';
            $this->won = false;
        }
    }

    session_start();
    // To prevent errors before submitting - Check if submitted
    if(isset($_POST['submit'])) {
        // Extract indices
        $indices = explode(',', $_POST['position']);
        $row = (int) $indices[0];
        $col = (int) $indices[1];
        // TODO - VALIDATE POSITION SELECTED
        $turn = $_SESSION['game']->turn;
        $_SESSION['game']->board[$row][$col] = $turn == 'X' ? 'X' : 'O';
        
        // Check logic & trigger overlay
        if (isWinner($row, $col)) {
            $_SESSION['game']->won = true;
        } else {
            // Take turns
            $_SESSION['game']->turn = $turn == 'X' ? 'O' : 'X';
        }

    } else {
        // store session
        $_SESSION['game'] = new Game();
    }

    function isWinner($row, $col) {
        $board = $_SESSION['game']->board;
        // Across row
        if ($board[$row][0] == $board[$row][1] && 
            $board[$row][1] == $board[$row][2]) {
            return true;    
        }
        // Down column
        elseif ($board[0][$col] == $board[1][$col] &&
                $board[1][$col] == $board[2][$col]) {
            return true;
        }   
        // Backslash Diagonal - only need to check if X=Y
        elseif ($row == $col &&
                $board[0][0] == $board[1][1] &&
                $board[1][1] == $board[2][2]) {
            return true;
        }
        // Forward slash Diagonal - only need to check if X+Y=2
        elseif ($row + $col == 2 &&
                $board[0][2] == $board[1][1] &&
                $board[1][1] == $board[2][0]) {
            return true;
        }
        return false;
    }
?>
<h2 class="text-center">Player <?php echo $_SESSION['game']->turn ?>'s Turn</h2>
<div class="d-flex justify-content-center">
    <form action=<?php echo $_SERVER['PHP_SELF']; ?> method="POST">
        <?php foreach($_SESSION['game']->board as $row_ind=>$row): ?>
            <div class="d-flex justify-content-center">
                <?php foreach($row as $col_ind=>$square): 
                    $posValue = "{$row_ind},{$col_ind}";
                    ?> 
                    <div class='border border-dark d-flex justify-content-center align-items-center' style="width: 100px; height: 100px;">
                        <?php if ($square == ''): ?> 
                            <input type="radio" name="position" value="<?php echo $posValue ?>" id="<?php echo $posValue ?>" class="d-none">
                            <label for="<?php echo $posValue ?>" class="w-100 h-100 d-flex justify-content-center align-items-center text-center"></label>
                            <?php else: ?>
                            <span class="fs-3"><?php echo $square ?></span>

                        <?php endif; ?>
                    </div>
                <?php  endforeach ?>
            </div>
        <?php  endforeach ?>
        <input type="submit" value="Submit Turn" name="submit">
    </form>
</div>
<?php if ($_SESSION['game']->won): ?>
    <form action=<?php echo $_SERVER['PHP_SELF']; ?> method="POST" class="d-flex flex-column justify-content-center align-items-center bg-success" style="position: relative; top: -50vh; min-height: 50vh;">
        <h1 class="text-center w-75 mb-5" style="z-index">PLAYER <?php echo $_SESSION['game']->turn; ?> WON!! </h1>
        <input type="submit" value="Restart" name="restart">
    </form>
<?php endif ?>
<?php include 'components/footer.php'; ?>