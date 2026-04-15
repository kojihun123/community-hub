# 🚀 CommunityHub

> **단순한 게시판을 넘어 실무적인 운영 로직을 담은 Laravel 커뮤니티 플랫폼**

CommunityHub는 Laravel 12를 기반으로 구축된 풀스택 커뮤니티 서비스입니다. 회원 간의 상호작용(게시글, 댓글, 좋아요, 신고)은 물론, 관리자의 운영 시스템(제재 이력 관리, 콘텐츠 숨김, 알림 송신)까지 실제 서비스 수준의 워크플로우를 구현하는 데 집중했습니다.

---

## 🛠 Tech Stack

### Core
* **Framework:** Laravel 12 (PHP 8.5)
* **Frontend:** Blade, Alpine.js, Tailwind CSS
* **Database:** MySQL 8.0
* **Caching/Messaging:** Redis

### Ecosystem & Tools
* **Auth:** Laravel Breeze (Session based)
* **Development:** Laravel Sail (Docker), Vite
* **Real-time:** Laravel Reverb & Echo (Broadcasting)
* **Testing:** PHPUnit

---

## ✨ Key Features

### 1. 커뮤니티 코어 (Community Core)
* **계층형 게시판:** 상위 분류(`board_groups`)와 하위 게시판(`boards`) 구조의 유연한 설계.
* **게시글/댓글:** CKEditor 기반 이미지 업로드, 무한 뎁스 대댓글(Self-referencing), 조회수 및 좋아요 토글.
* **검색 및 필터:** 제목, 작성자, ID 기반 검색 및 성능 최적화를 위한 Eager Loading 적용.

### 2. 운영 및 제재 시스템 (Moderation)
* **신고 시스템:** 게시글에 대한 신고 접수 및 관리자 검토 프로세스.
* **정교한 제재 로직:** 단순 삭제를 넘어 '경고', '기간 정지', '영구 정지' 등 제재 이력(`user_sanctions`) 관리.
* **콘텐츠 관리:** 운영 조치(`moderation_actions`)를 통한 콘텐츠 숨김 처리 및 조치 사유 기록.

### 3. 사용자 경험 (UX/Interaction)
* **인기글 시스템:** 실시간 계산의 부담을 줄이기 위해 선정 이력(`popular_posts`) 테이블과 스케줄러, Redis 캐시를 결합한 구조.
* **통합 알림:** 내 글의 반응(댓글, 좋아요) 및 운영 조치 결과에 대한 실시간 알림 제공.
* **마이페이지:** 내가 쓴 글/댓글 모아보기, 최근 방문 게시판(Session/Service 기반) 추적.

---

## 🔍 Technical Challenges & Resolutions

### 1. 효율적인 첨부파일 관리 (Temporary Attachments)
에디터에서 이미지만 올리고 글 작성을 취소할 경우 발생하는 '고아 파일' 문제를 해결하기 위해 `is_temporary` 상태 관리를 도입했습니다.
* **해결:** 업로드 시 임시 상태로 저장 후, 게시글 저장 시점에만 연결 확정. 미사용 파일은 Artisan 커맨드와 스케줄러를 통해 자동 정리하도록 설계했습니다.

### 2. 복잡한 폼 UI의 데이터 충돌 방지
한 화면에 댓글 작성, 수정, 답글 폼이 동시에 존재할 때 발생하는 `old()` 값 및 에러 메시지 혼선 문제를 해결했습니다.
* **해결:** 필드명을 역할별(`comment_content`, `edit_content` 등)로 명시적으로 분리하고, `FormRequest` 내부 헬퍼 메서드로 유효성 검사 대상을 동적으로 할당했습니다.

### 3. 중첩 라우트와 모델 바인딩 최적화
`/boards/{board:slug}/{post}`와 같은 구조에서 부모-자식 관계 검증 코드가 컨트롤러에 반복되는 문제를 발견했습니다.
* **해결:** Laravel의 `scopeBindings()`를 적용하여 라우팅 레벨에서 소속 관계를 보장함으로써 컨트롤러 로직을 획기적으로 단순화했습니다.


---

## 🗄 Database Architecture

* **Users:** 역할(`role`) 및 상태(`status`), 제재 종료일 관리.
* **Posts/Comments:** 작성자 정보 유지를 위해 탈퇴 시에도 이름을 남기는 `author_name_snapshot` 기법 적용.
* **Attachments:** 다형성 관계 대신 명확한 추적을 위해 게시글과 직접 연결 및 임시 상태 필드 보유.
* **Moderation Tables:** `reports`, `moderation_actions`, `user_sanctions` 3단 구조로 운영 투명성 확보.

---

## 🚀 Getting Started

1. **저장소 복제 및 환경 설정**
   ```bash
   git clone <repository-url>
   cp .env.example .env
   ```

2. **Docker(Sail) 실행**
   ```bash
   ./vendor/bin/sail up -d
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install
   ```

3. **키 생성 및 마이그레이션 (시더 포함)**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate --seed
   ```

4. **빌드 및 접속**
   ```bash
   ./vendor/bin/sail npm run dev
   # 접속: http://localhost
   ```

---

## 💡 Key Learnings
* 반복되는 UI를 `x-ui.section-card` 등 Blade 컴포넌트로 공통화하며 유지보수 효율성을 경험했습니다.
* 운영 데이터는 수정보다 **이력(History)을 쌓는 것**이 장애 대응과 CS 처리에 얼마나 중요한지 깨달았습니다.
* 단순히 기능을 만드는 것보다 `Service` 계층 분리와 `Model Scope` 활용을 통해 **"읽기 좋은 코드"**를 만드는 법을 익혔습니다.