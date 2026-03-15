# Laravel Community Platform Plan

## Overview

이 프로젝트는 `Laravel + Blade + Alpine.js` 기반의 완전한 커뮤니티형 사이트다.
목표는 단순한 게시판이나 블로그 수준이 아니라, 회원 활동과 관리자 운영 기능이 모두 포함된 `실제 서비스형 커뮤니티`를 만드는 것이다.

이 프로젝트는 다음 역량을 보여주는 데 집중한다.

- Laravel 라우팅, 컨트롤러, 미들웨어, 인증, 검증 흐름 이해
- Eloquent 기반 관계 설계와 커뮤니티 데이터 모델링
- 게시글, 댓글, 대댓글, 좋아요, 북마크, 신고 등 상호작용 기능 구현
- 관리자 페이지 직접 구현
- Blade 기반 서버 렌더링 구조 설계
- Alpine.js 기반 가벼운 인터랙션 처리
- Service 계층, Event / Job, Cache, API, 테스트 같은 중급 Laravel 요소 적용

핵심 원칙은 `실제 커뮤니티처럼 보이는 기능 구성`, `Laravel 정석 구현`, `운영 가능한 구조`다.

## Project Summary

### 프로젝트명

`CommunityHub`

### 한 줄 설명

회원가입, 게시글, 댓글, 대댓글, 좋아요, 북마크, 신고, 공지, 관리자 운영까지 포함한 풀 커뮤니티 플랫폼

### 왜 이 프로젝트가 좋은가

- 블로그보다 규모가 커 보이고 커뮤니티다운 느낌이 강하다.
- CRUD를 넘어서 인증, 권한, 상호작용, 운영 기능까지 보여줄 수 있다.
- Laravel의 핵심 기능과 중급 기능을 자연스럽게 녹이기 좋다.
- 취업 포트폴리오에서 "실제로 서비스 구조를 이해한다"는 인상을 줄 수 있다.

## Recommended Stack

### Core

- `PHP 8.5`
- `Laravel 12`
- `Blade`
- `Alpine.js`
- `MySQL`
- `Vite`
- `Tailwind CSS`

### Backend Enhancements

- `Laravel Sanctum`
- `Redis`
- `PHPUnit`
- `Laravel Reverb`
- `Laravel Echo`

이 프로젝트는 프론트 프레임워크보다 Laravel 본체를 중심에 두고, 필요한 범위에서만 Alpine.js를 사용한다.
버전 기준은 2026-03 현재 공식 문서 기준 최신 안정 버전을 우선 사용한다.

## Laravel Ecosystem Tools

이 프로젝트에서는 Laravel 본체 외에도 몇 가지 생태계 도구를 함께 사용한다.
아래는 `기본 포함된 것`과 `직접 추가 설치할 것`을 나눈 목록이다.

### Included By Default

- `Laravel Sail`
  Laravel 공식 Docker 기반 개발환경
- `Laravel Pail`
  로그를 보기 쉽게 확인하는 도구
- `Laravel Pint`
  코드 스타일 정리 도구
- `Laravel Tinker`
  Eloquent 조회나 간단한 코드 실행을 위한 REPL 도구
- `PHPUnit`
  Laravel 기본 테스트 실행 도구

### To Install / Add Explicitly

- `Laravel Breeze`
  회원가입, 로그인, 비밀번호 재설정 등 기본 인증 스타터킷
- `Alpine.js`
  Blade 위에서 토글, 모달, 드롭다운 같은 가벼운 인터랙션 처리
- `Tailwind CSS`
  화면 스타일링을 위한 유틸리티 CSS 프레임워크
- `Laravel Sanctum`
  인증이 필요한 API 보호용 패키지
- `Redis`
  캐시, 큐, 알림 보조 저장소로 사용할 인메모리 데이터 저장소
- `Laravel Reverb`
  Laravel 방식의 WebSocket / 브로드캐스팅 서버
- `Laravel Echo`
  프론트엔드에서 실시간 브로드캐스트 이벤트를 수신하는 클라이언트 라이브러리

### Usage Direction

- 초반에는 `Breeze`, `Alpine.js`, `Tailwind CSS`를 우선 적용한다.
- API가 필요해지면 `Sanctum`을 추가한다.
- 캐시, 큐, 알림 최적화가 필요해지면 `Redis`를 적극 사용한다.
- 실시간 알림을 붙일 때 `Reverb`와 `Echo`를 도입한다.

## Development Environment

개발환경은 `Laravel Sail` 기반으로 구성한다.
즉, 로컬에 PHP와 MySQL을 직접 맞추는 방식보다 Docker 기반의 Laravel 공식 개발환경을 사용하는 방향이다.

사용할 개발환경 구성은 다음과 같다.

- `Laravel Sail`
- `PHP 8.5`
- `Laravel 12`
- `MySQL`
- `Redis`
- `Mailpit`
- `Vite`

이 구성이 좋은 이유는 다음과 같다.

- Laravel 공식 방식이라 세팅이 비교적 안정적이다.
- Docker 기반 개발환경을 사용했다고 설명할 수 있다.
- Redis, Queue, Cache, 메일 테스트까지 자연스럽게 확장할 수 있다.
- 로컬 환경 차이를 줄이고 프로젝트 실행 방식을 통일할 수 있다.

세팅은 무겁게 커스터마이징하지 않고, `Sail 기본 구성을 사용해 프로젝트를 실행하는 수준`으로 가져간다.
즉 이 프로젝트에서 Docker 경험은 "컨테이너 기반 로컬 개발환경을 구성하고 운영한 경험"으로 설명하는 것이 적절하다.

## Main Features

### 1. 사용자 기능

- 회원가입
- 로그인 / 로그아웃
- 프로필 수정
- 비밀번호 변경
- 회원 탈퇴
- 내가 쓴 글 조회
- 내가 쓴 댓글 조회
- 내가 북마크한 글 조회
- 알림 확인

### 2. 게시판 기능

- 게시판 / 카테고리 시스템
- 게시글 목록
- 게시글 상세
- 게시글 작성 / 수정 / 삭제
- 공지글 / 상단 고정글
- 태그
- 검색
- 페이지네이션
- 조회수
- 인기글

### 3. 댓글 기능

- 댓글 작성 / 수정 / 삭제
- 대댓글 작성 / 수정 / 삭제
- 댓글 수 표시
- 관리자 댓글 숨김 / 삭제

### 4. 상호작용 기능

- 게시글 좋아요
- 북마크
- 신고
- 알림
- 실시간 알림

### 5. 관리자 기능

- 관리자 로그인 보호
- 관리자 대시보드
- 회원 관리
- 게시판 관리
- 게시글 관리
- 댓글 관리
- 신고 관리
- 공지 관리
- 사용자 제재 또는 콘텐츠 숨김 처리
- 제재 기간과 사유 관리
- 운영 처리 이력 기록
- 처리 결과를 대상 사용자에게 알림

### 6. Laravel 중급 기능

- Service 계층 분리
- Form Request 기반 검증
- API 2~4개 제공
- Event / Listener 적용
- Job / Queue 적용
- Redis 캐시 적용
- eager loading 적용
- PHPUnit Feature Test 작성

## Board Structure

기본 게시판 구조는 아래와 같이 시작한다.

- `공지사항`
- `자유게시판`
- `질문 / 답변`
- `정보공유`
- `프로젝트 자랑`

이 구조는 커뮤니티다운 인상을 주면서도 과하게 복잡하지 않은 수준이다.

## User Roles

### `guest`

- 게시글 / 댓글 읽기
- 회원가입 / 로그인

### `user`

- 게시글 작성 / 수정 / 삭제
- 댓글 / 대댓글 작성
- 좋아요
- 북마크
- 신고
- 프로필 관리

### `moderator`

- 신고 검토
- 게시글 / 댓글 숨김 처리
- 커뮤니티 운영 보조

### `admin`

- 전체 회원 관리
- 게시판 / 공지 관리
- 전체 콘텐츠 관리
- 운영자 권한 관리

## Site Structure

### Public / User Pages

#### `/`

메인 홈

- 공지글
- 인기글
- 최신글
- 게시판별 최신 글

#### `/boards/{slug}`

게시판 목록 페이지

- 게시글 목록
- 검색
- 정렬
- 페이지네이션

#### `/posts/{id}`

게시글 상세 페이지

- 본문
- 태그
- 조회수
- 좋아요
- 북마크
- 댓글 / 대댓글
- 신고 버튼

#### `/posts/create`

게시글 작성 페이지

#### `/posts/{id}/edit`

게시글 수정 페이지

#### `/profile/{username}`

사용자 프로필 페이지

- 기본 정보
- 작성 글
- 작성 댓글

#### `/me/posts`

내가 쓴 글 목록

#### `/me/comments`

내가 쓴 댓글 목록

#### `/me/bookmarks`

북마크 목록

#### `/notifications`

알림 목록

### Admin Pages

#### `/admin`

관리자 대시보드

- 회원 수
- 게시글 수
- 댓글 수
- 신고 대기 건수
- 인기 게시판 통계

#### `/admin/users`

회원 관리

#### `/admin/boards`

게시판 관리

#### `/admin/posts`

게시글 관리

#### `/admin/tags`

태그 관리

- 태그 목록
- 태그 생성 / 수정 / 삭제
- 사용 중인 게시글 수 확인

#### `/admin/comments`

댓글 관리

#### `/admin/reports`

신고 관리

#### `/admin/notices`

공지 관리

공지사항은 별도 전용 테이블이 아니라 `posts`를 기반으로 관리한다.
즉 일반 게시글과 같은 테이블에 저장하되, `is_notice`, `is_pinned`, `board_id` 조합으로 구분한다.
관리자 화면에서는 운영 편의를 위해 공지 전용 목록처럼 분리해 보여준다.

## Database Design

### `users`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| name | string | 사용자 이름 |
| username | string | 고유 사용자명 |
| email | string | 고유 이메일 |
| password | string | 비밀번호 |
| bio | text nullable | 자기소개 |
| avatar | string nullable | 프로필 이미지 |
| role | enum | `user`, `moderator`, `admin` |
| status | enum | `active`, `suspended`, `banned`, `withdrawn` |
| suspended_until | timestamp nullable | 기간 정지 종료 시각 |
| current_sanction_reason | text nullable | 현재 제재 요약 |
| withdrawn_at | timestamp nullable | 탈퇴 시각 |
| purge_scheduled_at | timestamp nullable | 완전 삭제 예정 시각 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

회원 탈퇴는 즉시 물리 삭제하지 않고 상태를 `withdrawn`으로 바꾼 뒤 로그인과 활동을 막는다.
이후 일정 기간이 지난 사용자 계정은 스케줄링 작업으로 완전 삭제하는 방식으로 설계한다.

### `boards`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| name | string | 게시판명 |
| slug | string | 고유 URL |
| description | text nullable | 설명 |
| is_active | boolean | 사용 여부 |
| sort_order | integer | 정렬 순서 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

### `posts`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| board_id | bigint | `boards.id` FK |
| user_id | bigint | `users.id` FK |
| title | string | 제목 |
| slug | string nullable | 선택적 slug |
| content | longText | 본문 |
| excerpt | text nullable | 요약 |
| author_name_snapshot | string | 작성자명 스냅샷 |
| status | enum | `published`, `hidden`, `deleted` |
| is_notice | boolean | 공지 여부 |
| is_pinned | boolean | 상단 고정 여부 |
| view_count | unsignedInteger | 조회수 |
| like_count | unsignedInteger | 좋아요 수 캐시 |
| comment_count | unsignedInteger | 댓글 수 캐시 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

### `comments`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| post_id | bigint | `posts.id` FK |
| user_id | bigint | `users.id` FK |
| parent_id | bigint nullable | 대댓글용 self FK |
| content | text | 본문 |
| author_name_snapshot | string | 작성자명 스냅샷 |
| status | enum | `visible`, `hidden`, `deleted` |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

### `tags`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| name | string | 태그명 |
| slug | string | 고유 URL |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

태그는 사용자가 자유 입력하는 방식이 아니라, 관리자가 생성하고 사용자는 게시글 작성 시 선택하는 방식으로 운영한다.

### `post_tag`

| Column | Type | Notes |
| --- | --- | --- |
| post_id | bigint | `posts.id` FK |
| tag_id | bigint | `tags.id` FK |

### `post_likes`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| post_id | bigint | `posts.id` FK |
| user_id | bigint | `users.id` FK |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

좋아요는 같은 사용자가 같은 게시글에 중복으로 생성할 수 없도록 `user_id + post_id` unique 제약을 둔다.
동작 방식은 "한 번 누르면 생성, 다시 누르면 취소"인 토글 방식으로 설계한다.

### `bookmarks`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| post_id | bigint | `posts.id` FK |
| user_id | bigint | `users.id` FK |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

북마크도 같은 사용자가 같은 게시글에 중복 저장할 수 없도록 `user_id + post_id` unique 제약을 둔다.
동작 방식은 "추가 / 해제" 토글 방식으로 설계한다.

### `reports`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| reporter_id | bigint | `users.id` FK |
| reportable_type | string | polymorphic type |
| reportable_id | bigint | polymorphic id |
| reason | text | 신고 사유 |
| status | enum | `pending`, `resolved`, `rejected` |
| handled_by | bigint nullable | `users.id` FK |
| moderation_action_id | bigint nullable | 대표 운영 조치 연결 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

### `user_sanctions`

사용자 제재 이력 테이블

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| user_id | bigint | `users.id` FK |
| report_id | bigint nullable | `reports.id` FK |
| type | enum | `warning`, `suspension`, `ban` |
| reason | text | 제재 사유 |
| started_at | timestamp | 제재 시작 시각 |
| ended_at | timestamp nullable | 기간 정지 종료 시각 |
| status | enum | `active`, `expired`, `revoked` |
| handled_by | bigint | 운영 처리자 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

### `moderation_actions`

콘텐츠 및 운영 처리 이력 테이블

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| report_id | bigint nullable | `reports.id` FK |
| target_type | string | `post`, `comment`, `user` |
| target_id | bigint | 대상 ID |
| action | enum | `hide`, `delete`, `restore`, `warn`, `suspend`, `ban` |
| reason | text | 처리 사유 |
| handled_by | bigint | 운영 처리자 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

### `notifications`

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | PK |
| user_id | bigint | 수신 사용자 |
| type | string | 알림 타입 |
| data | json | 알림 payload |
| read_at | timestamp nullable | 읽음 시각 |
| created_at | timestamp | 생성일 |
| updated_at | timestamp | 수정일 |

운영자가 게시글/댓글을 숨기거나 사용자를 제재한 경우에도 알림을 생성해 작성자 또는 대상 사용자에게 사유를 전달한다.

### Optional `attachments`

- 게시글 첨부 이미지 또는 파일 업로드용

## Application Structure

### Controllers

- 공개 영역 컨트롤러
- 사용자 활동 컨트롤러
- 관리자 영역 컨트롤러
- API 컨트롤러

### Services

- `PostService`
- `CommentService`
- `ReactionService`
- `ReportService`
- `NotificationService`
- `AdminModerationService`

서비스 계층을 두는 이유는 컨트롤러 비대화를 막고, 비즈니스 로직을 재사용 가능하게 만들기 위해서다.

### Requests

- `StorePostRequest`
- `UpdatePostRequest`
- `StoreCommentRequest`
- `StoreReportRequest`
- `UpdateProfileRequest`

### Middleware

- 인증 사용자 전용 접근 제어
- 관리자 페이지 보호
- 역할 기반 운영 기능 제어
- 필요 시 rate limit 적용

## Moderation Policy

운영 기능은 단순히 숨김 여부만 바꾸는 수준이 아니라, `현재 상태 + 제재 이력 + 처리 이력 + 사용자 통지` 구조로 설계한다.

- 사용자 상태는 `users.status`에서 현재 상태를 관리한다.
- 제재 기간과 사유는 `user_sanctions`에 누적 기록한다.
- 게시글/댓글 숨김 또는 삭제 같은 운영 조치는 `moderation_actions`에 남긴다.
- 운영 조치가 발생하면 대상 사용자에게 `notifications`로 사유를 알린다.

세 테이블의 역할은 다음과 같이 구분한다.

- `reports` : 사용자가 접수한 신고 기록
- `moderation_actions` : 운영자가 실제로 수행한 조치 기록
- `user_sanctions` : 사용자 제재의 기간, 사유, 상태 기록

권장 연결 방식은 다음과 같다.

- 신고가 들어오면 `reports` 생성
- 운영자가 콘텐츠를 숨기거나 삭제하면 `moderation_actions` 생성
- 신고 기반 처리였다면 `moderation_actions.report_id`로 연결
- 사용자 제재까지 이어지면 `user_sanctions` 생성
- 신고 기반 제재였다면 `user_sanctions.report_id`로 연결
- `reports.moderation_action_id`에는 대표 조치 1건을 연결할 수 있다.

즉 하나의 신고가 콘텐츠 조치와 사용자 제재를 동시에 유발할 수 있으며, 두 이력은 분리해 관리한다.

예시 제재 흐름은 다음과 같다.

- 경고
- 3일 정지
- 7일 정지
- 30일 정지
- 영구 정지

## Tag Policy

태그는 게시판보다 더 작은 주제 분류 단위로 사용한다.
초기에는 태그 난립을 막기 위해 `관리자 관리형 태그 정책`을 사용한다.

- 관리자가 태그를 생성 / 수정 / 삭제한다.
- 사용자는 게시글 작성 시 기존 태그만 선택할 수 있다.
- 게시글과 태그는 다대다 관계로 연결한다.
- 태그는 검색 보조, 주제 분류, 관련 글 연결에 활용한다.

## Account Deletion Policy

회원 탈퇴는 보통 즉시 완전 삭제보다 `유예 기간 후 삭제` 방식이 더 현실적이다.
이 프로젝트도 같은 방향으로 설계한다.

- 사용자가 탈퇴하면 계정 상태를 `withdrawn`으로 변경
- 즉시 로그인 불가 처리
- 프로필 공개 및 일반 활동 차단
- 기존 게시글과 댓글은 커뮤니티 기록 유지를 위해 삭제하지 않고 남긴다.
- 글과 댓글에는 `author_name_snapshot`을 저장해 탈퇴 후에도 화면 표시가 가능하게 한다.
- 탈퇴 후 화면에는 작성자를 `탈퇴한 사용자`로 표시하거나 스냅샷 기반 익명 표시를 사용한다.
- `purge_scheduled_at`에 삭제 예정 시각 저장
- 예: `30일 후 완전 삭제`
- 스케줄러가 주기적으로 대상 계정을 정리
- 완전 삭제 시 개인정보 필드는 제거하거나 익명화하고, 콘텐츠는 유지하는 방향을 기본 정책으로 한다.

이 방식은 실수로 탈퇴한 사용자의 복구 여지를 주고, 운영 정책도 설명하기 좋다.

## Notification Retention Policy

알림은 무기한 보관하지 않고, 읽음 여부와 중요도에 따라 정리한다.

- 읽지 않은 알림은 유지한다.
- 읽은 알림은 일정 기간 후 정리한다.
- 예: 일반 읽은 알림은 `90일 후 삭제`
- 운영 제재나 중요한 정책 알림은 더 길게 보관하거나 별도 기준을 둘 수 있다.
- 공지사항은 사용자별 notification row를 대량 생성하지 않고, 공지 콘텐츠 자체 또는 공지 배지 방식으로 우선 처리한다.

이 정책은 알림 테이블이 과도하게 커지는 것을 막고, 운영 비용을 낮추기 위한 것이다.

## API Scope

이 프로젝트에서 API는 모바일 앱이나 외부 클라이언트 확장 가능성을 보여주기 위한 보조 수단이다.
웹 화면이 메인이지만, API를 일부 제공함으로써 Laravel 백엔드 역량도 함께 드러낸다.

### Planned APIs

- `GET /api/posts`
- `GET /api/posts/{id}`
- `GET /api/boards`
- `GET /api/me`

필요 시 인증이 필요한 API는 `Sanctum`으로 보호한다.

## Event / Job Plan

- 댓글 작성 시 알림 이벤트 발생
- 게시글 좋아요 시 알림 이벤트 발생
- 신고 접수 시 운영 알림 이벤트 발생
- 게시글/댓글 숨김 처리 시 작성자 알림 이벤트 발생
- 사용자 제재 시 제재 알림 이벤트 발생
- 후속 처리나 집계 갱신은 Job으로 분리
- 실시간 알림이 필요한 경우 Broadcasting 이벤트로 확장
- Reverb와 Echo를 사용해 웹 클라이언트에서 새 알림을 즉시 반영

처음부터 모든 알림을 정교하게 만들기보다, 실시간 알림 흐름이 동작하도록 구현하고 이후 범위를 조정하는 방식으로 가져간다.

## Cache / Performance Plan

- 메인 홈 인기글 캐시
- 게시판별 최신글 캐시
- 알림 개수 캐시
- 게시글 목록 조회 시 eager loading 적용
- 검색과 정렬용 인덱스 고려

중요한 것은 대규모 트래픽을 과장하는 것이 아니라, 성능과 운영을 고려한 설계를 보여주는 것이다.

## MVP Scope

### Included

- 회원가입 / 로그인 / 로그아웃
- 회원 탈퇴
- 게시판 시스템
- 게시글 CRUD
- 댓글 / 대댓글 CRUD
- 좋아요
- 북마크
- 신고
- 공지글 / 상단 고정글
- 프로필
- 내가 쓴 글 / 댓글 / 북마크 조회
- 관리자 대시보드
- 회원 관리
- 게시판 관리
- 게시글 / 댓글 / 신고 관리
- 사용자 제재 이력 관리
- 운영 처리 이력 기록
- 알림
- 이미지 업로드
- 검색
- 페이지네이션
- Service 계층 분리
- Form Request 검증
- Sanctum API 2~4개
- Event / Job 1세트 이상
- Broadcasting / Reverb / Echo 적용
- Redis 캐시 1개 이상
- PHPUnit Feature Test 5개 이상

### Excluded

- 실시간 채팅
- DM
- 소셜 로그인
- 다국어
- 결제 기능
- 랭킹 시스템 고도화
- 추천 알고리즘
- 외부 OAuth 연동

## Job Posting Mapping

### Required Skills Mapping

- `Routing 사용` : 공개 / 회원 / 관리자 라우트 분리
- `Controller 작성` : 게시글, 댓글, 프로필, 관리자, API 컨트롤러
- `CRUD 구현` : 게시글, 댓글, 게시판, 공지, 신고 관리
- `운영 기능` : 사용자 제재, 콘텐츠 숨김, 운영 이력 관리
- `Eloquent ORM 사용` : 다대다, 일대다, 자기참조 댓글 관계 설계
- `Migration으로 DB 관리` : 핵심 테이블 전부 마이그레이션으로 관리
- `Blade 템플릿 사용` : 공개 및 관리자 화면 Blade 기반 구현
- `Form Validation` : Form Request 기반 검증
- `로그인 / 인증 기능` : 회원가입, 로그인, 인증 미들웨어
- `회원 관리 / 운영 정책` : 상태 기반 탈퇴 처리와 지연 삭제 정책

### Preferred Skills Mapping

- `REST API 개발 경험` : 게시글 / 게시판 / 내 정보 API 제공
- `Vue / React 경험` : 이번 프로젝트에서는 제외하고 `Alpine.js`로 인터랙션 처리
- `Redis / Queue 사용 경험` : 캐시와 알림 / 후처리 Job 적용
- `Broadcasting / WebSocket 경험` : 실시간 알림에 Reverb / Echo 적용
- `Docker 사용 경험` : 추후 로컬 개발 환경용으로 추가 가능
- `AWS / 서버 배포 경험` : MVP 이후 배포 단계에서 확장 가능

## Laravel Feature Mapping

- `Routing` : 공개, 사용자, 관리자, API 경로 분리
- `Middleware` : 인증, 관리자, 역할 제어
- `Controllers` : 각 도메인 요청 처리
- `Services` : 게시글, 댓글, 신고, 알림 로직 분리
- `Moderation Design` : 제재 기간, 사유, 처리 이력, 사용자 통지 설계
- `Requests / Validation` : 입력 검증
- `Eloquent` : 커뮤니티 핵심 관계 설계
- `Eager Loading` : 목록/상세 최적화
- `Blade Components` : 레이아웃과 공통 UI 분리
- `Alpine.js` : 토글, 모달, 드롭다운, 인터랙션 처리
- `Storage` : 이미지 업로드 관리
- `API + Sanctum` : 모바일/외부 확장 대비 API 제공
- `Events / Jobs` : 알림과 후속 처리 분리
- `Broadcasting / Reverb / Echo` : 실시간 알림 전달
- `Cache / Redis` : 홈/목록 데이터 캐시
- `PHPUnit` : 핵심 기능 테스트

## Build Priority

1. Laravel 기본 세팅
2. 인증 기능 구현
3. 사용자, 게시판, 게시글, 댓글, 신고 마이그레이션 설계
4. 공개 홈 및 게시판/게시글 화면 구현
5. 게시글 CRUD 구현
6. 댓글 / 대댓글 구현
7. 좋아요, 북마크, 신고 구현
8. 프로필, 내가 쓴 글/댓글/북마크 화면 구현
9. 관리자 대시보드 및 운영 기능 구현
10. 검색, 페이지네이션, eager loading 적용
11. 이미지 업로드 및 Alpine.js 인터랙션 추가
12. API 구현
13. Event / Job 적용
14. Broadcasting / Reverb / Echo 기반 실시간 알림 적용
15. Redis 캐시 적용
16. PHPUnit 테스트 작성

## What To Emphasize In Portfolio

- 왜 블로그보다 커뮤니티를 선택했는지
- 직접 관리자 시스템까지 구현한 이유
- 커뮤니티 데이터 관계를 어떻게 설계했는지
- 게시글, 댓글, 대댓글, 좋아요, 신고를 어떻게 분리했는지
- 운영자 입장에서 어떤 moderation 기능을 제공했는지
- 사용자 제재 기간, 사유, 처리 이력을 어떻게 설계했는지
- API, Event, Job, Broadcasting, Cache, Test를 어떤 이유로 넣었는지
- Laravel을 단순 CRUD가 아니라 서비스 구조로 사용했다는 점

## Definition Of Done

- 회원이 가입하고 로그인할 수 있다.
- 회원이 게시글, 댓글, 대댓글을 작성할 수 있다.
- 좋아요, 북마크, 신고가 동작한다.
- 관리자가 회원, 게시글, 댓글, 신고, 공지를 운영할 수 있다.
- 사용자 제재와 콘텐츠 숨김 처리 시 사유와 이력이 남고, 대상 사용자에게 알림이 전달된다.
- 검색, 페이지네이션, 캐시, eager loading이 적용되어 있다.
- API, Event / Job, Broadcasting, Redis, PHPUnit이 최소 범위 이상 프로젝트에 실제 반영되어 있다.
- 전체 프로젝트가 `실제 커뮤니티 서비스를 만들 수 있는 Laravel 개발자`라는 인상을 준다.
