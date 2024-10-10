<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/*
This function checks for the winner by evaluating all possible winning combinations.
*/
function whoIsWinner() {
    // Check rows, columns, and diagonals for a winner
    $winning_combinations = [
        ['1-1', '2-1', '3-1'], // top row
        ['1-2', '2-2', '3-2'], // middle row
        ['1-3', '2-3', '3-3'], // bottom row
        ['1-1', '1-2', '1-3'], // left column
        ['2-1', '2-2', '2-3'], // middle column
        ['3-1', '3-2', '3-3'], // right column
        ['1-1', '2-2', '3-3'], // diagonal left to right
        ['3-1', '2-2', '1-3'], // diagonal right to left
    ];

    foreach ($winning_combinations as $combination) {
        $winner = checkWhoHasTheSeries($combination);
        if ($winner != null) return $winner;
    }
    
    return null; // No winner yet
}

/*
This function checks if all three positions in a list are occupied by the same player ('X' or 'O').
It returns 'X' if all 3 items are 'X', 'O' if all 3 items are 'O', or null otherwise.
*/
function checkWhoHasTheSeries($list) {
    $XCount = 0;
    $OCount = 0;

    foreach ($list as $value) {
        if (isset($_SESSION['grid'][$value])) {
            if ($_SESSION['grid'][$value] == 'X') {
                $XCount++;
            } elseif ($_SESSION['grid'][$value] == 'O') {
                $OCount++;
            }
        }
    }

    if ($XCount == 3) return 'X';
    if ($OCount == 3) return 'O';
    return null;
}

// Check if reset was requested
if (isset($_POST['reset'])) {
    session_destroy();  // Destroy the current session to reset the game
    header("Location: " . $_SERVER['PHP_SELF']);  // Reload the page to start a new game
    exit();
}

// Initialize the game grid and turn tracking if not already set
if (!isset($_SESSION['grid'])) {
    $_SESSION['grid'] = [
        '1-1' => '', '2-1' => '', '3-1' => '',
        '1-2' => '', '2-2' => '', '3-2' => '',
        '1-3' => '', '2-3' => '', '3-3' => ''
    ];
    $_SESSION['turn'] = 'X';  // X always goes first
    $_SESSION['game_over'] = false; // Initialize game over state
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['reset'])) {
    foreach ($_POST as $key => $value) {
        // Only update if the game is not over and the spot is empty
        if (isset($_SESSION['grid'][$key]) && $_SESSION['grid'][$key] == '' && !$_SESSION['game_over']) {
            $_SESSION['grid'][$key] = $_SESSION['turn'];
            $_SESSION['turn'] = ($_SESSION['turn'] == 'X') ? 'O' : 'X';  // Alternate turn
        }
    }

    // Check if there is a winner
    $winner = whoIsWinner();
    if ($winner || is_draw()) {
        $_SESSION['game_over'] = true;
        echo $winner ? "<p>The winner is: $winner!</p>" : "<p>It's a draw!</p>";
    }
}

// Function to display the game grid
function display_grid() {
    echo '<table>';
    for ($row = 1; $row <= 3; $row++) {
        echo '<tr>';
        for ($col = 1; $col <= 3; $col++) {
            $position = $col . '-' . $row;
            $cell_value = $_SESSION['grid'][$position];
            if ($cell_value == '' && !$_SESSION['game_over']) {
                // Display button if the cell is empty and the game is not over
                echo "<td><button type='submit' name='$position'></button></td>";
            } else {
                // Display X or O in its respective color if chosen
                $color = $cell_value == 'X' ? 'green' : 'red';
                echo "<td style='background-color: $color;'><p>$cell_value</p></td>";
            }
        }
        echo '</tr>';
    }
    echo '</table>';
}

// Check if the game is a draw
function is_draw() {
    foreach ($_SESSION['grid'] as $cell) {
        if ($cell == '') {
            return false;  // Not a draw if there's still an empty cell
        }
    }
    return true; // All cells are filled, and it's a draw
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <style>
        button {
            background-color: #3498db;
            height: 100%;
            width: 100%;
            font-size: 20px;
            color: white;
            border: 0px;
        }

        table td {
            width: 75px;
            height: 75px;
            font-size: 20px;
            border: 3px solid #040404;
        }

        button:hover, button:focus {
            background-color: #04469d;
        }
    </style>
</head>
<body>
    <h1>Tic Tac Toe</h1>
    
    <!-- Game Form -->
    <form method="POST" action="">
        <?php display_grid(); ?>
    </form>

    <!-- Reset Button Form -->
    <form method="POST" action="">
        <input type="submit" name="reset" value="Reset Game">
    </form>
</body>
</html>
