<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game</title>
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


        canvas {
            border: 1px solid black;
        }

        #hpBar {
            width: 160px;
            height: 20px;
            background-color: gray;
            margin-top: 10px;
        }

        #hpFill {
            height: 100%;
            background-color: red;
        }

        .game-container {
            display: flex;
            justify-content: space-between;
            /* Mengatur jarak antara elemen */
            align-items: center;
        }

        .game {
            flex: 1;
            /* Menggunakan ruang tersedia yang lebih besar */
            position: relative;
            /* Diperlukan untuk mengatur posisi absolut pada child */
        }

        .info {
            width: 500px;
            /* Pusatkan teks */
        }

        #stopwatch {
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <section>
        <div class="game-container">
            <div class="game">
                <canvas id="gameCanvas" width="800" height="600"></canvas>
                <div id="hpBar">
                    <div id="hpFill"></div>
                </div>
                <script>
                    const canvas = document.getElementById('gameCanvas');
                    const ctx = canvas.getContext('2d');
                    const character = { x: 0, y: 0, size: 50, speed: 10, targetX: 0, targetY: 0 };
                    const characterImg = new Image();
                    characterImg.src = 'Asset/sticker_4.png'; // Sesuaikan dengan path gambar karakter Anda
                    const bgImg = new Image();
                    bgImg.src = 'Asset/pxfuel.jpg'; // Sesuaikan dengan path gambar background Anda
                    const enemy = { x: Math.random() * (canvas.width - 50), y: canvas.height - 200, size: 200, speed: 2, direction: Math.random() < 0.5 ? 'right' : 'left', image1: new Image(), image2: new Image(), currentImage: 1, hp: 5000 };
                    enemy.image1.src = 'Asset/Icon_Sucrose_2.webp'; // Sesuaikan dengan path gambar musuh 1
                    enemy.image2.src = 'Asset/Icon_02_Sucrose_4.webp'; // Sesuaikan dengan path gambar musuh 2

                    let bullets = [];


                    function drawEnemy() {
                        if (enemy.currentImage === 1) {
                            if (enemy.direction === 'left') {
                                ctx.drawImage(enemy.image1, enemy.x, enemy.y, enemy.size, enemy.size);
                            } else {
                                ctx.save(); // Simpan status canvas sekarang
                                ctx.scale(-1, 1); // Balik gambar ke arah kiri
                                ctx.drawImage(enemy.image1, -enemy.x - enemy.size, enemy.y, enemy.size, enemy.size);
                                ctx.restore(); // Kembalikan status canvas sebelumnya
                            }
                        } else {
                            if (enemy.direction === 'left') {
                                ctx.drawImage(enemy.image2, enemy.x, enemy.y, enemy.size, enemy.size);
                            } else {
                                ctx.save(); // Simpan status canvas sekarang
                                ctx.scale(-1, 1); // Balik gambar ke arah kiri
                                ctx.drawImage(enemy.image2, -enemy.x - enemy.size, enemy.y, enemy.size, enemy.size);
                                ctx.restore(); // Kembalikan status canvas sebelumnya
                            }
                        }
                    }


                    function checkEnemyCollision() {
                        bullets.forEach((bullet, bulletIndex) => {
                            if (bullet.y < enemy.y + enemy.size && bullet.y + bullet.size > enemy.y &&
                                bullet.x < enemy.x + enemy.size && bullet.x + bullet.size > enemy.x) {
                                enemy.hp -= 100;
                                if (enemy.hp <= 0) {
                                    // Pindah ke halaman selamat.php jika HP musuh habis
                                    window.location.href = 'selamat.php';
                                }
                                enemy.currentImage = 2;
                                setTimeout(() => {
                                    enemy.currentImage = 1;
                                }, 500);
                                bullets.splice(bulletIndex, 1);
                            }
                        });
                    }


                    function moveEnemy() {
                        if (enemy.direction === 'right') {
                            enemy.x += enemy.speed;
                        } else {
                            enemy.x -= enemy.speed;
                        }

                        if (enemy.x + enemy.size > canvas.width) {
                            enemy.direction = 'left';
                        } else if (enemy.x < 0) {
                            enemy.direction = 'right';
                        }
                    }
                    function drawCharacter() {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        // Gambar background
                        ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
                        ctx.save(); // Simpan status canvas sekarang
                        if (character.targetX > character.x) {
                            // Jika bergerak ke kanan, gambar normal
                            ctx.drawImage(characterImg, character.x, character.y, character.size, character.size);
                        } else {
                            // Jika bergerak ke kiri, flip gambar
                            ctx.scale(-1, 1);
                            ctx.drawImage(characterImg, -character.x - character.size, character.y, character.size, character.size);
                        }
                        ctx.restore(); // Kembalikan status canvas sebelumnya
                    }

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'ArrowRight') {
                            character.targetX += character.speed;
                        } else if (event.key === 'ArrowLeft') {
                            character.targetX -= character.speed;
                        }
                    });

                    function moveCharacter() {
                        if (Math.abs(character.x - character.targetX) > character.speed) {
                            if (character.x < character.targetX) {
                                character.x += character.speed;
                            } else {
                                character.x -= character.speed;
                            }
                        }
                        if (Math.abs(character.y - character.targetY) > character.speed) {
                            if (character.y < character.targetY) {
                                character.y += character.speed;
                            } else {
                                character.y -= character.speed;
                            }
                        }
                    }


                    function checkCharacterCollision() {
                        if (character.y + character.size > enemy.y && character.y < enemy.y + enemy.size &&
                            character.x + character.size > enemy.x && character.x < enemy.x + enemy.size) {
                            // Kurangi HP karakter jika terkena musuh
                            hp -= 10;
                        }
                    }

                    function checkCollision() {
                        bullets.forEach((bullet, bulletIndex) => {
                            if (bullet.y < enemy.y + enemy.size && bullet.y + bullet.size > enemy.y &&
                                bullet.x < enemy.x + enemy.size && bullet.x + bullet.size > enemy.x) {
                                // Kurangi HP musuh jika terkena tembakan
                                enemy.hp -= 100;
                                if (enemy.hp <= 0) {
                                    // Jika HP musuh habis, musnahkan musuh
                                    enemy.hp = 0;
                                    // Tambahkan efek ke musuh saat mati
                                    // Misalnya, ledakan atau animasi lainnya
                                    // Selanjutnya, Anda dapat menambahkan logika untuk menghapus musuh dari layar
                                    // Contoh: enemies.splice(enemyIndex, 1);
                                }
                                // Tampilkan gambar kedua
                                enemy.currentImage = 2;
                                setTimeout(() => {
                                    enemy.currentImage = 1;
                                }, 500); // Tampilkan gambar pertama setelah 500ms
                                bullets.splice(bulletIndex, 1); // Hapus peluru yang mengenai musuh
                            }
                        });

                        // Periksa tabrakan karakter dengan musuh
                        checkCharacterCollision();
                    }



                    function drawHPBar() {
                        const hpFill = document.getElementById('hpFill');
                        hpFill.style.width = enemy.hp + '%'; // Mengubah lebar elemen HPFill sesuai dengan nilai HP musuh
                    }


                    function drawBullets() {
                        ctx.fillStyle = 'white';
                        bullets.forEach(bullet => {
                            ctx.fillRect(bullet.x, bullet.y, bullet.size, bullet.size);
                        });
                    }

                    function moveBullets() {
                        bullets.forEach(bullet => {
                            bullet.y += bullet.speed;
                        });
                    }

                    function createBullet() {
                        const bullet = { x: character.x + character.size / 2, y: character.y + character.size, size: 5, speed: 5 };
                        bullets.push(bullet);
                    }

                    canvas.addEventListener('click', createBullet);

                    function draw() {
                        drawCharacter();
                        drawHPBar();
                        drawBullets();
                        drawEnemy(); // Menggambar musuh
                    }

                    function gameLoop() {
                        moveCharacter();
                        moveBullets();
                        moveEnemy();
                        checkCollision();
                        checkEnemyCollision();
                        draw();

                        if (enemy.hp <= 0) {
                            // Refresh halaman jika HP musuh habis
                            location.reload();
                        } else {
                            requestAnimationFrame(gameLoop);
                        }
                    }

                    // Load gambar karakter dan background setelah gambar selesai dimuat
                    characterImg.onload = () => {
                        bgImg.onload = () => {
                            gameLoop();
                        };
                    };
                </script>
            </div>

            <div class="info">
                <div id="stopwatch">00:00:00</div>
                <script>
                    // Ambil elemen stopwatch
                    const stopwatchElement = document.getElementById('stopwatch');

                    let startTime; // Waktu mulai stopwatch
                    let elapsedTime = 0; // Waktu yang sudah berlalu

                    // Fungsi untuk memformat waktu dalam format hh:mm:ss
                    function formatTime(ms) {
                        const date = new Date(ms);
                        const hours = date.getUTCHours().toString().padStart(2, '0');
                        const minutes = date.getUTCMinutes().toString().padStart(2, '0');
                        const seconds = date.getSeconds().toString().padStart(2, '0');
                        return `${hours}:${minutes}:${seconds}`;
                    }

                    // Fungsi untuk menampilkan waktu di stopwatch
                    function displayElapsedTime() {
                        stopwatchElement.textContent = formatTime(elapsedTime);
                    }

                    // Fungsi untuk memperbarui waktu setiap detik
                    function updateElapsedTime() {
                        const currentTime = new Date().getTime();
                        elapsedTime = currentTime - startTime;
                        displayElapsedTime();
                    }

                    // Fungsi untuk memulai stopwatch
                    function startStopwatch() {
                        startTime = new Date().getTime() - elapsedTime;
                        setInterval(updateElapsedTime, 1000); // Perbarui waktu setiap detik
                    }

                    // Mulai stopwatch saat halaman dimuat
                    startStopwatch();
                </script>
                Bunuh Musuhnya<br>
                tombol <br>
                <><br>
                    kiri kanan<br>
            </div>
        </div>
    </section>
</body>

</html>