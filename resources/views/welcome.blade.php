<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنجاز سكور | منصة حجز تأشيرات السعودية</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --saudi-green: #006C35;
            --saudi-gold: #D4AF37;
            --dark-blue: #1a2e35;
            --soft-gray: #f4f7f6;
            --accent-red: #e63946;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background-color: #ffffff;
            color: var(--dark-blue);
            overflow-x: hidden;
        }

        /* --- الهيدر --- */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--saudi-green);
        }

        .btn {
            padding: 8px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            font-size: 0.95rem;
            display: inline-block;
        }

        .btn-outline {
            border: 2px solid var(--saudi-green);
            color: var(--saudi-green);
        }

        .btn-solid {
            background: var(--saudi-green);
            color: white;
            border: 2px solid var(--saudi-green);
            box-shadow: 0 4px 12px rgba(0, 108, 53, 0.2);
        }

        /* --- قسم الهيرو --- */
        .hero-section {
            padding: 60px 8%;
            display: flex;
            align-items: center;
            gap: 50px;
            min-height: 80vh;
            background: radial-gradient(circle at top left, #f9fdfb 0%, #ffffff 100%);
        }

        .hero-text {
            flex: 1;
        }

        .hero-text h1 {
            font-size: 3rem;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-text h1 span {
            color: var(--saudi-green);
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #5a6d75;
            margin-bottom: 35px;
            max-width: 550px;
        }

        /* --- تنسيق الصورة التعبيرية --- */
        .hero-image {
            flex: 1;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .passport-img {
            width: 100%;
            max-width: 450px;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            transform: rotate(-3deg);
            /* حركة بسيطة لإعطاء حيوية */
            transition: transform 0.5s ease;
            border: 8px solid white;
        }

        .passport-img:hover {
            transform: rotate(0deg) scale(1.03);
        }

        /* شكل زخرفي خلف الجواز */
        .hero-image::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--saudi-green);
            filter: blur(80px);
            opacity: 0.1;
            z-index: -1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* --- الإحصائيات --- */
        .steps-container {
            background: var(--soft-gray);
            padding: 50px 8%;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .step-item h3 {
            font-size: 2.2rem;
            color: var(--saudi-green);
        }

        @media (max-width: 992px) {
            .hero-section {
                flex-direction: column;
                text-align: center;
                padding: 40px 5%;
            }

            .hero-text h1 {
                font-size: 2.2rem;
            }

            .passport-img {
                max-width: 300px;
                transform: rotate(0);
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-area">
            <div class="logo-text">إنجاز سكور</div>
        </div>
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="btn btn-outline">تسجيل الدخول</a>
        </div>
    </header>

    <section class="hero-section">
        <div class="hero-text">
            <h1>منصتك الأسرع لطلب <span>تأشيرة السعودية</span> عبر إنجاز</h1>
            <p>نحن نوفر لك تقنية متطورة لتعبئة بيانات "إنجاز" وحجز مواعيد التأشيرة في وقت قياسي وبدقة متناهية، لضمان
                قبول طلبك بدون أخطاء.</p>
            <a href="{{ route('login') }}" class="btn btn-solid" style="padding: 15px 40px; font-size: 1.1rem;">ابدأ طلب
                جديد
                الآن</a>
        </div>

        <div class="hero-image">
            <!-- صورة تعبيرية لجواز سفر سعودي (رابط بديل احترافي) -->
            <img src="{{ asset('img/Gemini_Generated_Image_9x3vz49x3vz49x3v.png') }}" alt="Passport and Travel"
                class="passport-img">
        </div>
    </section>

    <div class="steps-container">
        <div class="step-item">
            <h3>+50k</h3>
            <p>تأشيرة منجزة</p>
        </div>
        <div class="step-item">
            <h3>99%</h3>
            <p>نسبة القبول</p>
        </div>
        <div class="step-item">
            <h3>24/7</h3>
            <p>دعم فني مباشر</p>
        </div>
    </div>

    <script>
        < script type = "text/javascript" >
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();

        // جزء ربط بيانات المستخدم المسجل
        @auth
        Tawk_API.visitor = {
            name: '{{ auth()->user()->name }}',
            email: '{{ auth()->user()->email }}'
        };
        @endauth

        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/6a0352178acb811c36855663/1joefhbbp';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    </script>
</body>

</html>
