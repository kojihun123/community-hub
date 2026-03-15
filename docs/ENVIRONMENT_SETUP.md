# Environment Setup

## Overview

이 문서는 `CommunityHub` 프로젝트의 로컬 개발환경 세팅 방법을 정리한 문서다.
기준 스택은 `Laravel 12 + Laravel Sail + Blade + Breeze`다.

## Prerequisites

다음 도구가 먼저 설치되어 있어야 한다.

- `Docker`
- `Composer`
- `Node.js / npm`
- `PHP` CLI

## 1. Laravel Project Creation

프로젝트 폴더에서 Laravel 프로젝트를 생성한다.

```bash
composer create-project laravel/laravel .
```

## 2. Sail Installation

MySQL, Redis, Mailpit을 포함한 Sail 구성을 설치한다.

```bash
php artisan sail:install --with=mysql,redis,mailpit
```

## 3. Start Containers

Sail 컨테이너를 실행한다.

```bash
./vendor/bin/sail up -d
```

## 4. App Initialization

애플리케이션 키를 생성하고 기본 마이그레이션을 실행한다.

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

## 5. Breeze Installation

Blade 기반 기본 인증 스타터킷을 설치한다.

```bash
./vendor/bin/sail composer require laravel/breeze --dev
./vendor/bin/sail artisan breeze:install blade
```

## 6. Frontend Packages

프론트엔드 의존성을 설치한다.

```bash
./vendor/bin/sail npm install
```

필요하면 Alpine.js를 명시적으로 설치한다.

```bash
./vendor/bin/sail npm install alpinejs
```

## 7. Frontend Build

Vite 개발 서버를 실행한다.

```bash
./vendor/bin/sail npm run dev
```

## 8. Final Migration Check

Breeze 설치 후 인증 관련 테이블까지 다시 마이그레이션한다.

```bash
./vendor/bin/sail artisan migrate
```

## 9. Access URLs

세팅 완료 후 다음 주소를 확인한다.

- App: `http://localhost`
- Mailpit: `http://localhost:8025`

## Notes

- `Tailwind CSS`는 Breeze 설치 과정에서 함께 설정되는 경우가 많다.
- `Alpine.js`는 Blade 기반 인터랙션에 사용한다.
- `Redis`, `Sanctum`, `Reverb`, `Echo`는 기능 구현 단계에서 추가로 활성화하면 된다.
- Docker 기반 개발환경 경험은 `Laravel Sail`을 사용했다고 설명하면 된다.
