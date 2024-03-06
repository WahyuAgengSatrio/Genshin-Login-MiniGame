<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Page</title>
    <style>
        body {
            background-color: white;
            margin: 0;
            padding: 0;
        }

        video {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            position: fixed;
            top: 0;
            left: 0;
        }

        #clickMessage {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: none;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1;
            /* Menempatkan di depan video */
        }
    </style>
</head>

<body>
    <video id="video1" autoplay>
        <source src="../Asset/gi-doorappear.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <p id="clickMessage" style="background-color: transparent;">Click here to continue...</p>

    <video id="video2" style="display: none;">
        <source src="../Asset/gidooropem.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <script>
        const video1 = document.getElementById('video1');
        const video2 = document.getElementById('video2');
        const clickMessage = document.getElementById('clickMessage');

        // Tampilkan pesan setelah video pertama selesai diputar
        video1.addEventListener('ended', () => {
            clickMessage.style.display = 'block';
        });

        // Pindah ke video kedua setelah pesan diklik
        clickMessage.addEventListener('click', () => {
            video1.pause();
            video1.style.display = 'none';
            clickMessage.style.display = 'none';
            video2.style.display = 'block';
            video2.play();
        });

        // Redirect ke halaman utama setelah video kedua selesai diputar
        video2.addEventListener('ended', () => {
            window.location.href = '../game.php';
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>