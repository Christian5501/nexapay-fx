# NexaPay FX 💱

**Cross-border payments & FX platform for Africa, starting in Rwanda.**
Send money to mobile money wallets and bank accounts with live exchange rates.

> **Status: working demo / MVP.** Rates are live and transactions are stored in MySQL, but payouts are **simulated**. No real money moves until a licensed payment provider (e.g. MTN MoMo API, Flutterwave) is integrated.

## Screenshots
<!-- Add images after uploading them to a /docs folder, for example: -->
<!-- ![Dashboard](docs/dashboard.png) -->

## Features
- Live FX rates for 12 currencies (USD, EUR, GBP, RWF, KES, UGX, TZS, GHS, NGN, XOF, ZAR, ETB) from ExchangeRate-API, cached for 5 minutes
- Send Money flow: details, review, confirm, with the rate and fee locked at submission
- Transactions saved in MySQL with unique reference numbers
- Dashboard with total volume, success rate and fees earned
- Mobile money providers: MTN, Airtel, M-Pesa, Orange Money, Wave, Equity Bank
- REST API for rates, conversion, transfers, transactions and stats
- Responsive 3D glass design that works on phones (bottom navigation, slide-up forms)

## Tech stack
PHP 8 · MySQL · JavaScript · Chart.js · custom CSS

## Project structure
```
nexapay2/
├── index.php            # Main app (dashboard, send money, rates)
├── api/index.php        # REST API
├── includes/
│   ├── config.example.php   # Copy to config.php and edit
│   └── db.php               # Database + FX functions
└── assets/css/style.css
```

## Run locally (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\nexapay2`
2. Start **Apache** and **MySQL** in XAMPP
3. In phpMyAdmin, create an empty database named `nexapay` (tables are created automatically)
4. Copy `includes/config.example.php` to `includes/config.php`
5. Get a free key at https://www.exchangerate-api.com and set `FX_API_KEY` in `includes/config.php`
6. Open http://localhost/nexapay2

Without an API key, the app runs on built-in demo rates.

## API
| Method | Endpoint | Purpose |
|---|---|---|
| GET | `api/?action=rates` | All live rates |
| GET | `api/?action=convert&amount=100&from=USD&to=RWF` | Convert an amount |
| POST | `api/?action=transfer` | Create a transfer (JSON body) |
| GET | `api/?action=transactions` | List transactions |
| GET | `api/?action=stats` | Platform statistics |

## Roadmap
- [ ] User accounts, login and KYC
- [ ] Real payout integration with a licensed provider
- [ ] API authentication and restricted CORS
- [ ] Transaction webhooks and SMS receipts

## Author
Built by Christian · [GitHub @Christian5501](https://github.com/Christian5501)