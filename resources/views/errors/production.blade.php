<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error {{ $status ?? 500 }}</title>
    <style>
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: linear-gradient(180deg, #f7f3ea 0%, #efe6d1 100%);
            color: #2f2417;
            min-height: 100vh;
            display: grid;
            place-items: center;
        }

        .error-shell {
            width: min(680px, calc(100% - 32px));
            background: rgba(255, 252, 245, 0.94);
            border: 1px solid rgba(120, 91, 28, 0.18);
            border-radius: 20px;
            box-shadow: 0 24px 70px rgba(72, 52, 12, 0.12);
            padding: 36px 32px;
        }

        .eyebrow {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #8b6a25;
            margin-bottom: 14px;
        }

        h1 {
            margin: 0;
            font-size: 42px;
            line-height: 1;
            color: #5e4514;
        }

        p {
            margin: 18px 0 0;
            font-size: 17px;
            line-height: 1.6;
            color: #57452a;
        }

        .actions {
            margin-top: 28px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .actions a {
            text-decoration: none;
            border-radius: 999px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 700;
        }

        .primary {
            background: #8b6a25;
            color: #fffaf0;
        }

        .secondary {
            border: 1px solid rgba(94, 69, 20, 0.22);
            color: #5e4514;
            background: transparent;
        }
    </style>
</head>
<body>
    <section class="error-shell">
        <div class="eyebrow">Application Error</div>
        <h1>{{ $status ?? 500 }}</h1>
        <p>{{ $message ?? 'An unexpected error occurred. Please try again shortly.' }}</p>
        <div class="actions">
            <a class="primary" href="{{ url('/') }}">Return Home</a>
            <a class="secondary" href="javascript:history.back()">Go Back</a>
        </div>
    </section>
</body>
</html>