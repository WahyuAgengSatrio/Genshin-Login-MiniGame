<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="icon" type="image/png" href="Asset/LogoK.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @font-face {
            font-family: 'MyCustomFont';
            src: url('Asset/zh-cn.ttf') format('truetype');
        }

        body {
            margin: 0;
            font-family: 'MyCustomFont', sans-serif;
            overflow: hidden;
        }

        .glass-blur.flex-container {
            display: none;
        }


        .container {
            position: relative;
            margin-top: 15vh;
            scrollbar-width: hidden;
        }

        .glass-blur {
            border-radius: 20px;
            padding: 15px;
            margin-top: 20vh;
            justify-content: space-between;
            transition: background 0.5s ease, color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 400px;
            margin: auto;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            /* Ubah warna teks menjadi transparan */
        }

        .glass-blur:hover {

            color: black;
            background: rgba(255, 255, 255, 0.9);
            /* Mengurangi opacity untuk tampilan yang lebih jelas */
        }


        form {
            display: flex;
            flex-direction: column;
            padding: 10px;
            /* Mengurangi padding form */
        }

        label {
            margin-top: 5px;
            /* Mengurangi margin-top pada label */
            font-size: 0.8rem;
            /* Mengubah ukuran font label */
        }

        input,
        textarea {
            padding: 8px;
            /* Mengurangi padding pada input dan textarea */
            margin-top: 3px;
            /* Mengurangi margin-top pada input dan textarea */
            font-size: 0.8rem;
            /* Mengubah ukuran font input dan textarea */
        }

        button {
            padding: 8px;
            /* Mengurangi padding pada button */
            font-size: 0.8rem;
            /* Mengubah ukuran font button */
            margin-top: 5px;
            /* Mengurangi margin-top pada button */
        }


        a {
            color: #4CAF50;
            text-decoration: none;
        }

        video {
            object-fit: cover;
            width: 100%;
            height: 100%;
            position: absolute;
        }

        body,
        html {
            height: 100%;
            margin: 0;
        }

        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
        }

        #toggleBlurButton img:hover {
            filter: brightness(50%);
        }
    </style>
</head>

<body>
    <div class="container">
        <video autoplay muted loop id="video-background">
            <source src="Asset/gi-waitscene.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <div class="glass-blur flex-container">

            <!-- Login -->
            <div class="login-container" id="loginFormContainer">
                <form id="loginForm" action="Fungctions/login_process.php" method="post" class="p-4 form-slide">
                    <h2 class="mb-5 mx-auto">
                        <img src="" alt="Your Logo" style="max-height: 50px;">
                    </h2>
                    <div class="mb-4">
                        <input type="text" class="form-control" id="login-username" name="username"
                            placeholder="Username" required
                            style="background-color: transparent; border-color: gray; color: dark;">
                        <input type="password" class="form-control" id="login-password" name="password"
                            placeholder="Password" required
                            style="background-color: transparent; border-color: gray; color: dark;">
                    </div>
                    <button type="submit" class="btn btn-outline-warning ">Login</button>
                </form>
            </div>
        </div>
    </div>

    <button id="toggleBlurButton" class="position-fixed bottom-0 end-0 m-4" style="background: none; border: none;">
        <img src="Asset/btn-gs.jpeg" alt="Toggle Blur" style="width: 30px; border-radius: 50%;">
    </button>

    <button id="toggleMusicButton" class="position-fixed bottom-0 start-0 m-4" style="background: none; border: none;">
        <i class="fas fa-play" style="font-size: 30px;"></i>
    </button>

    <audio id="backgroundMusic" loop>
        <source src="Asset/gs-bs.mp3" type="audio/mp3">
        Your browser does not support the audio element.
    </audio>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const musicButton = document.getElementById('toggleMusicButton');
        const backgroundMusic = document.getElementById('backgroundMusic');

        let isMusicPlaying = false;

        musicButton.addEventListener('click', () => {
            if (isMusicPlaying) {
                backgroundMusic.pause();
                isMusicPlaying = false;
                musicButton.innerHTML = '<i class="fas fa-play" style="font-size: 30px;"></i>';
                sessionStorage.setItem('isMusicPlaying', 'false'); // Simpan status pemutaran lagu
            } else {
                backgroundMusic.play();
                isMusicPlaying = true;
                musicButton.innerHTML = '<i class="fas fa-pause" style="font-size: 30px;"></i>';
                sessionStorage.setItem('isMusicPlaying', 'true'); // Simpan status pemutaran lagu
            }
        });

        window.addEventListener('load', () => {
            const isMusicPlaying = sessionStorage.getItem('isMusicPlaying');
            if (isMusicPlaying === 'true') {
                const lastPlayedTime = sessionStorage.getItem('lastPlayedTime');
                if (lastPlayedTime) {
                    backgroundMusic.currentTime = parseFloat(lastPlayedTime);
                }
                backgroundMusic.play();
                musicButton.innerHTML = '<i class="fas fa-pause" style="font-size: 30px;"></i>';
            }
        });

        window.addEventListener('beforeunload', () => {
            if (isMusicPlaying) {
                sessionStorage.setItem('lastPlayedTime', backgroundMusic.currentTime);
            }
        });

        document.getElementById('toggleBlurButton').addEventListener('click', function () {
            const blurContainer = document.querySelector('.glass-blur.flex-container');
            if (blurContainer.style.display === 'none' || getComputedStyle(blurContainer).display === 'none') {
                blurContainer.style.display = 'flex';
            } else {
                blurContainer.style.display = 'none';
            }
        });
    </script>
</body>

</html>