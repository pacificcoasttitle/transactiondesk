<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableRealtorData extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('pct_realtor_data');
        $table->addColumn('agent', 'string')
                ->addColumn('company', 'string')
                ->addColumn('address', 'string')
                ->addColumn('city', 'string')
                ->addColumn('state', 'string')
                ->addColumn('zip', 'string')
                ->create();
        //Fetch data from PMA DB
        try {

          $mysqli=new mysqli("propprofile.db.8460164.hostedresource.com","propprofile","locusV1!","propprofile");
          if($mysqli->connect_errno) {
                echo "Failed to connect to MySQL: " . $mysqli->connect_error;
          }
          else{
            $result = $mysqli->query("SELECT agent,company,address,city,state,zip
             FROM pmaformdata");
            $agents = array();
            $companies = array();
            $agentChecks = array();
            $companyChecks = array();
            while($row = $result->fetch_assoc()) { 
              $agent = $row['agent'];
              $agent = trim($agent);
              $agent = preg_replace('/\s+/', ' ', $agent);
              $agentCheck = preg_replace('/\s+/', '', $agent);
              $agentCheck = strtoupper($agentCheck);
              if (!in_array($agentCheck ,$agentChecks)) {
                $agentChecks[] = $agentCheck;
                $agents[] = $row;
              }
            }

            $table = $this->table('pct_realtor_data');
            $table->insert($agents)
                  ->save();
            
          }
        }
        catch(Exception $ex) {
          
        }

    }
}
