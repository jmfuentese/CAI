<div class="section">
    <main>
        <center>
            <h5 class="">Session information</h5>
            <div class="container">
                <div class='col s24'>
                    <table id="dataTable" class="striped responsive-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Student</th>
                            <th>Teacher</th>
                            <th>Group</th>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Session Entry</th>
                            <th>Session End</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $a= new Controller();
                        $a-> sessionsListController();?>
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
    </main>