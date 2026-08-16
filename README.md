<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

npm install leaflet leaflet-routing-machine



After Installation
Restart your terminal / IDE (close and reopen Command Prompt or your IDE terminal) so the newly added environment variables take effect.
Verify the installation:
cmd
git --version
Initialize your repository:
cmd
D:\gitProject> git init
2:43 PM
2:49 PM
Git successfully install ho chuka hai! 👍

Abhi error isliye aayi kyunki terminal ko restart nahi kiya gaya tha, isliye terminal ko naya PATH nahi mila.

Step 1: Terminal ko refresh karein (Ya Restart karein)
Apna Terminal / IDE close karke dobara open karein, ya fir current terminal me ye command run karein:

Agar PowerShell me hain:

powershell
$env:Path = "C:\Program Files\Git\cmd;" + $env:Path
Agar Command Prompt (CMD) me hain:

cmd
set "PATH=C:\Program Files\Git\cmd;%PATH%"
Ab git --version check karenge toh version dikha dega.

Step 2: Git User Info Configure karein (Sirf Pehli Baar)
Commit karne se pehle apna Git name aur email set kar lein:

cmd
git config --global user.name "Rishi Jadaun"
git config --global user.email "your-github-email@example.com"
(Apna actual GitHub email daalein)

Step 3: Commands Ek-Ek Karke Run Karein
D:\gitProject directory me ye commands run karein:

README file banayein:

powershell
echo "# myHome" > README.md
Git repository initialize karein:

cmd
git init
Files stage karein:

Sirf README add karne ke liye:
cmd
git add README.md
(Optional) Agar poore project ki saari files add karni hain:
cmd
git add .
Commit banayein:

cmd
git commit -m "first commit"
Branch ka naam main set karein:

cmd
git branch -M main
GitHub repository link karein:

cmd
git remote add origin https://github.com/rishijadaun/myHome.git
(Agar already origin added ka error aaye toh git remote set-url origin https://github.com/rishijadaun/myHome.git use karein)

GitHub par push karein:

cmd
git push -u origin main