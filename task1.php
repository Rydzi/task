<?php  


/**
 * class for find & count pools and find the biggest pool on table
 * input is square or rectangle
 */
class Table
{
  
    private $maxRows, $maxCols, $matrix;

    // create empty matrix of visited positions
    private $visitedMatrix = [ [ ] ];

    // create empty array of pools size
    private $poolsSize = [ ];

    public $pools = 0;
    public $biggestPool = 0;

    // coordinates of 8 neighbours
    const rowNeighbours = [-1, -1, -1, 0, 0, 1, 1, 1]; 
    const colNeighbours = [-1, 0, 1, -1, 1, -1, 0, 1]; 

    public function __construct($matrix)
    {
        $this->matrix = $matrix;    
        $this->getMaxDimensions();
    }

    //find the max row and column
    private function getMaxDimensions()
    {
        $this->maxRows = count($this->matrix);
        $this->maxCols = count($this->matrix[0]);
    }
    
    //check if cell is exists and eligible for DFS
    private function isSafe($row, $col) 
    {
        // row exists 
        // if row doesnt exists further condition are not relevant and return false
        if ( !( ( ($row >= 0) && ($row < $this->maxRows) ) &&  ( ($col >= 0) && ($col < $this->maxCols) ) ) ){
            return false;
        }

        // row contains pool
        $isPool = $this->matrix[$row][$col];

        // row is not visited
        $notVisited = !isset($this->visitedMatrix[$row][$col]);
        
        return ( $notVisited && $isPool);
    } 
    
    // Depth-first search 
    private function DFS($row, $col) 
    { 
        // mark this cell as visited
        $this->visitedMatrix[$row][$col] = true; 

        // increase size of current pool 
        $this->poolsSize[$this->pools]++;
        
        // Recursion for searching neighbours
        for ($i = 0; $i < 8; $i++) {
            $nextRow = $row + self::rowNeighbours[$i];
            $nextCol = $col + self::colNeighbours[$i];

            if ( $this->isSafe( $nextRow, $nextCol) ) {
                $this->DFS($nextRow, $nextCol); 
            }
        }
    } 
    
    // number of pools and their sizes
    public function countPools() 
    { 
        // walk through the matrix

        for ($i = 0; $i < $this->maxRows; $i++) {
            for ($j = 0; $j < $this->maxCols; $j++) {
                // if finds pool and position is not visited
                if ( $this->matrix[$i][$j] &&  !isset($this->visitedMatrix[$i][$j]) ){                               
                    //count the pool which is also number of current pool
                    $this->pools++;   
                    //inicialize key for array poolsSize
                    $this->poolsSize[$this->pools] = 0;
                    //look on neighbours
                    $this->DFS($i, $j);              
                }                                
                                            
            }
        }
        //check if at least 1 pool found
        if($this->pools){
            $this->biggestPool = max($this->poolsSize);
        }
    } 

    //write matrix for testing purpose
    public function writeMatrix()
    {
        for ($i = 0; $i < $this->maxRows; $i++) {
            for ($j = 0; $j < $this->maxCols; $j++) {
                if ( $this->matrix[$i][$j] ){
                    echo '<span style="color: #cf682d;background-color: #cf682d;">0</span>';
                }
                else{
                    echo '<span style="color: #3b362e; background-color:#3b362e;">0</span>';
                }
            }
            echo "<br>";
        }
    }
}

// Sample square matrix
$squareMatrix = [
    [0, 1, 1, 0, 0], 
    [0, 1, 0, 0, 1], 
    [1, 0, 0, 1, 1], 
    [0, 0, 1, 0, 0], 
    [0, 0, 1, 0, 1],
]; 

// Sample rectangle matrix
$rectangleMatrix = [
    [0, 1, 1, 0, 0, 0], 
    [0, 1, 1, 0, 0, 1], 
    [1, 1, 0, 0, 1, 1], 
    [0, 0, 0, 0, 1, 1], 
    [0, 0, 0, 1, 1, 1],
]; 

// Sample matrix without pools
$noPoolMatrix = [
    [0, 0, 0, 0, 0], 
    [0, 0, 0, 0, 0], 
    [0, 0, 0, 0, 0], 
    [0, 0, 0, 0, 0], 
    [0, 0, 0, 0, 0], 
]; 
// sample empty matrix
$emptyMatrix = [ [ ] ];

// Sample matrix
$matrix = $squareMatrix;

$table = new Table($matrix);
$table->writeMatrix();
$table->countPools();

  
echo 'Number of pools on table is: ' . ($table->pools) . '<br>'; 
echo 'The biggest pool contains : ' . ($table->biggestPool) .' units.<br/>'; 
  
