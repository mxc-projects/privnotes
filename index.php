<!doctype html>
<html lang="en">
    <head>
        <title>Privnote By Hoxedzik666 / @devilprojects_pl</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>
    <body>
        <header>

           
        </header>
        <?php
        if(!isset($_GET['msg_id'])) {
            echo '<main class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh; width: 100vw;">
            <div style="width: 55vw">';
                if(isset($_GET['error']) && $_GET['error'] == 'empty') {
                    echo '<div class="alert alert-danger" role="alert">
                    Please write a message.
                    </div>';
        
                }
              echo'<h4 class="mb-5 pb-5">created by @devilprojects_pl</h4>
              <h1 class="mb-2">Create a Privnote</h1>      
                <p class="mb-5 pb-4 text-decoration-underline">Create a note that will self-destruct after being read.</p>
                <form method="post" action="src/engine/php/privmsg.php">
                    <div class="mb-3">
                        <label for="message" class="form-label">Write your message here...</label>
                        <textarea
                            class="form-control"
                            id="message"
                            name="message"
                            rows="6"
                            style="border: 1px solid #ced4da;"  
                        ></textarea>
                    </div>
                    <button type="submit" name="create_msg" class="btn btn-secondary btn-lg">Create Note</button>
                </form>
            </div>
        </main>';
        } if(isset($_GET['msg_id']) && !isset($_GET['check'])) {
            echo '<main class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh; width: 100vw;">
            <div style="width: 55vw">
                <h1 class="mb-2">Your Privnote</h1>
                <p class="mb-5 pb-4 text-decoration-underline">This note will self-destruct after being read.</p>
                <form>
                    <input type="text" class="form-control mb-3" value="http://localhost/privnote/index.php?msg_id='.$_GET['msg_id'].'&check=0" readonly>
                </form>
                ';
        }
        if(isset($_GET['msg_id']) && isset($_GET['check']) && $_GET['check'] == 0) {

            require_once 'src/engine/db/db.php';
            $db = new database('localhost','root','','privnote');
            $sql = "SELECT * FROM messages WHERE id = ".$_GET['msg_id'];
            $result = $db->query($sql);
            $row = $result->fetch();
            if($row) {
                $iv = $row['iv'];
                $encrypted_msg = $row['message'];
                $encryption_key = $row['secure_key'];
                $ciphering = "AES-128-CTR";
                $options = 0;

                // Decrypt the message using openssl_decrypt()
                $decryption = openssl_decrypt($encrypted_msg, $ciphering, $encryption_key, $options, $iv);

                echo '
                <div style="width: 100vw; height: 100vh;" class="d-flex align-items-center justify-content-center">
                    <form>
                        <div class="alert alert-danger" role="alert">
                            This note will self-destruct after being read.
                        </div>
                        <div class="mb-3" style="width: 55vw;">
                            <label for="message" class="form-label">Decrypted message...</label>
                            <textarea
                                class="form-control"
                                id="message"
                                name="message"
                                rows="6"
                                columns="50"
                                style="border: 1px solid #ced4da;"
                                readonly
                            >'.$decryption.'</textarea>
                        </div>';
                        $sql = "DELETE FROM messages WHERE id = ".$_GET['msg_id'];
                        $result = $db->query($sql);
        
                        if($result) {
                            echo '<a href="index.php" class="btn btn-secondary">Create another note</a>';
                            
                        } else {
                            echo '
                            <div class="alert alert-danger" role="alert">
                            This note could not be destroyed.
                            </div>';
                        }
                   echo'</form>';
            echo '</div>';

                

            }
            $db -> dissconnect();
        }
        ?>
        <footer>

        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>