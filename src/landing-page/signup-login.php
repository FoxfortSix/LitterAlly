<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LitterAlly</title>
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css"> -->
    <link rel="stylesheet" href="../output.css">
  <!-- Small CSS to Hide elements at 1520px size -->
  <style>
    @media(max-width:1520px) {
      .left-svg {
        display: none;
      }
    }

    /* small css for the mobile nav close */
    #nav-mobile-btn.close span:first-child {
      transform: rotate(45deg);
      top: 4px;
      position: relative;
      background: #a0aec0;
    }

    #nav-mobile-btn.close span:nth-child(2) {
      transform: rotate(-45deg);
      margin-top: 0px;
      background: #a0aec0;
    }
  </style>

  <!-- CHARTS -->
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <script src="../assets/js/init-alpine.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
  
</head>

<body class="overflow-x-hidden antialiased scroll-smooth">
  <!-- Header Section -->
  <header class="fixed top-0 z-50 w-full bg-white shadow h-15">
    <div
      class="container relative flex items-center justify-center h-full max-w-6xl px-8 mx-auto sm:justify-between xl:px-0">
      <a href="./index.php" class="relative flex items-center inline-block h-5 h-full font-black leading-none">
        <svg class="w-auto h-6 text-indigo-600 fill-current" viewBox="0 0 194 116" xmlns="http://www.w3.org/2000/svg">
          <g fill-rule="evenodd">
            <path d="M96.869 0L30 116h104l-9.88-17.134H59.64l47.109-81.736zM0 116h19.831L77 17.135 67.088 0z" />
            <path d="M87 68.732l9.926 17.143 29.893-51.59L174.15 116H194L126.817 0z" />
          </g>
        </svg>
        <span class="ml-3 text-xl text-gray-800">LitterAlly<span class="text-pink-500">.</span></span>
      </a>
    </div>
  </header>
  <!-- End Header Section-->

  <?php
  session_start();
  if (isset($_SESSION['error'])) {
      echo "<script>alert('" . addslashes($_SESSION['error']) . "');</script>";
      unset($_SESSION['error']);
  }
  ?>

  <!-- FORM WRAPPER -->
  <div id="formWrapper" class="flex justify-center px-4 pt-20 overflow-hidden sm:px-6 lg:px-8">
    <!-- LOGIN FORM -->
    <div id="loginForm"
      class="absolute w-full mt-20 transition-all duration-500 ease-in-out translate-x-10 opacity-0 pointer-events-none">
      <div class="relative w-full mx-auto overflow-hidden bg-white shadow-2xl lg:max-w-3xl sm:max-w-5xl rounded-xl">
        <!-- Grid Container -->
        <form action="signin_process.php" method="POST" class="relative z-10 grid grid-cols-1 md:grid-cols-2">
          <!-- Left Side -->
          <div class="relative flex flex-col items-center justify-center px-6 py-16 space-y-6 text-white bg-[#5A63E6]">
            <!-- SVG Separator -->
            <svg class="absolute top-0 hidden h-full -right-12 md:block" viewBox="0 0 110 877" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M48.7998 0L85.3998 219.25L24.3998 438.5L109.8 657.75L-0.000190735 877V657.75L-0.000190735 438.5L-0.000190735 219.25L-0.000190735 0L48.7998 0Z"
                fill="#5A63E6" />
            </svg>

            <!-- Form Content -->
            <div class="w-full max-w-sm">
              <h2 class="text-3xl font-bold text-center">Welcome Back</h2>
              <p class="mb-6 text-sm text-center">
                Silakan masuk untuk melanjutkan aktivitas Anda. <br />
                Kami senang melihat Anda kembali.
              </p>

              <input type="email" name="email" placeholder="Email"
                class="w-full p-3 mb-4 font-semibold text-black bg-white rounded-xl" />
              <input type="password" name="password" placeholder="Password"
                class="w-full p-3 mb-4 font-semibold text-black bg-white rounded-xl" />

              <button name="login"
                class="w-full p-3 font-bold text-white transition duration-300 transform bg-pink-500 rounded-xl hover:scale-105 hover:bg-pink-600">Login</button>
            </div>
          </div>

          <!-- Right Side -->
          <div class="relative flex flex-col items-center justify-center px-6 py-16 space-y-6 text-center">
            <div class="z-10 max-w-sm">
              <h2 class="text-3xl font-bold">New Here?</h2>
              <p class="mt-2 text-sm text-gray-700">
                Daftar sekarang dan mulai berkontribusi untuk perubahan! Pantau data sampah yang dibuang, Analisis
                pengelolaan, Solusi pintar untuk lingkungan hijau.
              </p>
            </div>

            <button id="toSignUpBtn"
              class="px-6 py-3 font-bold text-white transition duration-300 transform bg-indigo-600 hover:scale-105 rounded-xl hover:bg-indigo-700">Sign
              Up</button>

            <!-- Decorative Circles -->
            <div class="absolute w-12 h-12 border-4 border-pink-500 rounded-full -z-10 top-5 left-5"></div>
            <div class="absolute w-8 h-8 bg-pink-500 rounded-full -z-10 bottom-5 left-10"></div>
            <div class="absolute w-4 h-4 bg-pink-500 rounded-full -z-10 bottom-10 left-20"></div>
            <div class="absolute w-6 h-6 bg-pink-500 rounded-full -z-10 top-10 right-10"></div>
            <div class="absolute w-10 h-10 border-4 border-pink-500 rounded-full -z-10 bottom-10 right-10"></div>
          </div>
        </form>
      </div>
    </div>

    <!-- SIGNUP FORM -->
    <div id="signupForm"
      class="absolute w-full transition-all duration-500 ease-in-out -translate-x-10 opacity-0 pointer-events-none mt-15">
      <div class="relative w-full mx-auto overflow-hidden bg-white shadow-2xl lg:max-w-3xl sm:max-w-5xl rounded-xl">
        <!-- Grid Container -->
        <form action="signup-process.php" method="POST" class="relative z-10 grid grid-cols-1 md:grid-cols-2">
          <!-- Left Side -->
          <div class="relative flex flex-col items-center justify-center px-6 py-16 space-y-6 text-black bg-[#FFFF]">
            <!-- SVG Separator -->
            <svg class="absolute top-0 z-10 hidden h-full -right-12 md:block" viewBox="0 0 110 877" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M48.7998 0L85.3998 219.25L24.3998 438.5L109.8 657.75L-0.000190735 877V657.75L-0.000190735 438.5L-0.000190735 219.25L-0.000190735 0L48.7998 0Z"
                fill="#FFF" />
            </svg>

            <!-- Form Content -->
            <div class="z-10 w-full max-w-sm">
              <h2 class="text-3xl font-bold text-center">Welcome!</h2>
              <p class="mb-6 text-sm text-center">
                Terima kasih telah bergabung bersama kami. Silakan lengkapi formulir pendaftaran di bawah ini untuk memulai. <br />
              </p>

              <!-- Username -->
              <input type="text" name="nama_user" placeholder="Username" required
                class="w-full p-3 mb-4 font-semibold text-white bg-pink-500 rounded-xl" />

              <!-- Email -->
              <input type="email" name="email" placeholder="Email" required
                class="w-full p-3 mb-4 font-semibold text-white bg-pink-500 rounded-xl" />

              <!-- Password -->
              <input type="password" name="password" placeholder="Password" required
                class="w-full p-3 mb-4 font-semibold text-white bg-pink-500 rounded-xl" />

              <!-- Confirm Password -->
              <input type="password" name="confirm_password" placeholder="Confirm Password" required
                class="w-full p-3 mb-4 font-semibold text-white bg-pink-500 rounded-xl" />

              <!-- Submit Button -->
              <button
                class="w-full px-6 py-3 font-bold text-white transition duration-300 transform bg-indigo-600 hover:scale-105 rounded-xl hover:bg-indigo-700">
                Sign Up
              </button>
            </div>

            <div class="absolute w-12 h-12 border-4 border-pink-500 rounded-full top-5 left-5"></div>
            <div class="absolute w-8 h-8 bg-pink-500 rounded-full bottom-5 left-10"></div>
            <div class="absolute w-4 h-4 bg-pink-500 rounded-full bottom-10 left-20"></div>
            <div class="absolute w-6 h-6 bg-pink-500 rounded-full top-10 right-10"></div>
            <div class="absolute w-10 h-10 border-4 border-pink-500 rounded-full bottom-10 right-10"></div>
          </div>

          <!-- Right Side -->
          <div class="bg-[#5A63E6] relative flex flex-col items-center justify-center px-6 py-16 space-y-6 text-center">
            <div class="z-10 max-w-sm">
              <h2 class="text-3xl font-bold text-white">Sudah Punya Akun?</h2>
              <p class="mt-2 text-sm text-white">
                Daftar sekarang dan mulai berkontribusi untuk perubahan! Pantau data sampah yang dibuang, Analisis
                pengelolaan, Solusi pintar untuk lingkungan hijau.
              </p>
            </div>

            <button id="toLoginBtn"
              class="px-6 py-3 font-bold text-white transition duration-300 transform bg-pink-500 rounded-xl hover:scale-105 hover:bg-pink-600">Sign
              In</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- a little JS for the mobile nav button -->
  <script>
    if (document.getElementById('nav-mobile-btn')) {
      document.getElementById('nav-mobile-btn').addEventListener('click', function () {
        if (this.classList.contains('close')) {
          document.getElementById('nav').classList.add('hidden');
          this.classList.remove('close');
        } else {
          document.getElementById('nav').classList.remove('hidden');
          this.classList.add('close');
        }
      });
    }

    // SHOW FORM LOGIN / SIGN UP
    function showForm(type) {
      const loginForm = document.getElementById("loginForm");
      const signupForm = document.getElementById("signupForm");

      if (type === "signup") {
        loginForm.classList.remove("opacity-100", "translate-x-0", "pointer-events-auto");
        loginForm.classList.add("opacity-0", "translate-x-10", "pointer-events-none");

        signupForm.classList.remove("opacity-0", "-translate-x-10", "pointer-events-none");
        signupForm.classList.add("opacity-100", "translate-x-0", "pointer-events-auto");
      } else {
        signupForm.classList.remove("opacity-100", "translate-x-0", "pointer-events-auto");
        signupForm.classList.add("opacity-0", "-translate-x-10", "pointer-events-none");

        loginForm.classList.remove("opacity-0", "translate-x-10", "pointer-events-none");
        loginForm.classList.add("opacity-100", "translate-x-0", "pointer-events-auto");
      }
    }

    window.addEventListener("DOMContentLoaded", () => {
      const hash = window.location.hash;
      if (hash === "#signup") {
        showForm("signup");
      } else {
        showForm("login");
      }

      // OPTIONAL: Event listener untuk tombol "Sign Up" dan "Sign In" di dalam halaman
      document.getElementById("toSignUpBtn")?.addEventListener("click", (e) => {
        e.preventDefault();
        showForm("signup");
      });

      document.getElementById("toLoginBtn")?.addEventListener("click", (e) => {
        e.preventDefault();
        showForm("login");
      });
    });
  </script>
</body>

</html>