# CommunityHub Development Plan

## Overview

이 문서는 [`PLAN.md`](/home/jhko/portfolio/community-hub/docs/PLAN.md)의 설계를 실제 구현 작업으로 옮기기 위한 개발 체크리스트다.
목표는 모든 기능을 한 번에 구현하는 것이 아니라, `핵심 기능부터 단계적으로 완성`하는 것이다.

## Recommended Build Order

1. 개발환경과 인증 기반을 먼저 고정한다.
2. 홈 화면은 하드코딩 리스트로 먼저 구성해 UI 흐름을 잡는다.
3. 게시판, 게시글, 댓글이 동작하는 최소 커뮤니티를 만든다.
4. 관리자 화면으로 핵심 리소스를 운영 가능하게 만든다.
5. 좋아요, 신고 같은 커뮤니티 상호작용을 붙인다.
6. 제재, 숨김, 탈퇴 정책 같은 운영 기능을 완성한다.
7. 마지막에 API, 캐시, 이벤트, 실시간, 테스트를 보강한다.

### Step 1 - Bootstrap

- [x] 개발환경 확인
- [x] Breeze 인증 적용
- [x] 기본 레이아웃과 네비게이션 구성
- [x] 공통 Blade 컴포넌트 기초 정리

### Step 2 - Home First

- [x] 홈 화면 큰 박스 구조 구성
- [x] 홈 화면 하드코딩 리스트 구성
- [x] 홈 섹션 제목 / 리스트 아이템 UI 정리
- [x] 홈 인기글을 실제 데이터와 캐시 기반으로 교체
- [x] 홈 인기글 더보기와 전용 페이지 흐름 정리
- [x] 홈 최근 방문 게시판 구성
- [x] 홈 운영 공지 구성

### Step 3 - Data First

- [x] 핵심 마이그레이션 작성
- [x] 모델 생성 및 관계 정의
- [x] 기본 상위분류 / 게시판 시더 작성
- [x] 신고 / 운영 / 제재용 핵심 테이블 추가

### Step 4 - Core Community

- [x] 게시판 목록
- [x] 게시글 목록 / 상세
- [x] 게시글 작성 / 수정 / 삭제
- [x] 댓글 / 대댓글 작성 / 수정 / 삭제
- [x] 검색 / 페이지네이션

### Step 5 - Admin Basics

- [ ] 관리자 접근 제어
- [ ] 관리자 대시보드
- [x] 신고 목록 / 처리 UI
- [ ] 상위분류 / 게시판 / 게시글 / 댓글 / 공지 관리
- [x] 사용자 역할 / 상태 구조 정리

### Step 6 - Interaction

- [x] 좋아요
- [x] 조회수
- [x] 인기글 선정 이력 구조
- [x] 인기글 선정 커맨드 / 스케줄
- [x] 홈 인기글 노출
- [x] 인기글 전용 페이지
- [x] 게시판 목록 인기글 배지 표시
- [x] 운영 처리 기반 알림 생성
- [x] 알림 목록

### Step 7 - Moderation

- [x] 신고 기능
- [x] 콘텐츠 숨김 / 삭제
- [x] 사용자 경고 / 정지 / 차단
- [x] 운영 이력 / 제재 이력
- [ ] 회원 탈퇴 정책 반영

### Step 8 - Advanced Laravel Features

- [ ] Service 계층 분리
- [ ] Form Request 분리
- [ ] Sanctum API
- [ ] Event / Job
- [ ] Redis 캐시
- [ ] Reverb / Echo
- [ ] PHPUnit 테스트

## Phase 1 - Core Community

### Environment

- [x] Laravel 12 프로젝트 생성
- [x] Laravel Sail 기반 개발환경 구성
- [x] MySQL 연결
- [x] Redis 연결
- [x] Mailpit 연결
- [x] Tailwind / Vite 동작 확인

### Auth

- [x] 회원가입
- [x] 로그인 / 로그아웃
- [x] 비밀번호 변경
- [ ] 관리자 로그인 보호
- [ ] 역할 기반 접근 제어 기본 적용

### Database

- [x] `users` 마이그레이션
- [x] `users` 커뮤니티 프로필 / 상태 컬럼 추가
- [x] `board_groups` 마이그레이션
- [x] `boards` 마이그레이션
- [x] `posts` 마이그레이션
- [x] `attachments` 마이그레이션
- [x] `comments` 마이그레이션
- [x] `post_likes` 마이그레이션
- [x] `popular_posts` 마이그레이션
- [x] `reports` 마이그레이션
- [x] `moderation_actions` 마이그레이션
- [x] `user_sanctions` 마이그레이션
- [x] `notifications` 마이그레이션
- [x] 기본 상위분류 / 게시판 시더 작성

### Public Pages

- [x] 메인 홈 페이지
- [x] 홈 하드코딩 리스트 구성
- [x] 홈 하드코딩 데이터를 실제 인기글 데이터로 교체
- [x] 홈 최근 방문 게시판 데이터 연결
- [x] 홈 운영 공지 데이터 연결
- [x] 인기글 전용 페이지
- [x] 게시판 목록 페이지
- [x] 게시글 상세 페이지
- [x] 게시글 작성 페이지
- [x] 게시글 수정 페이지
- [ ] 게시판 목록 접기 / 펼치기 같은 Alpine.js 인터랙션 추가
- [x] 마이페이지 프로필 수정 페이지
- [ ] 내가 쓴 글 페이지
- [ ] 내가 쓴 댓글 페이지

### Post / Comment Features

- [x] 게시글 CRUD
- [x] 게시글 본문 이미지 업로드
- [x] 임시 첨부 업로드 및 게시글 연결
- [x] 사용되지 않은 임시 첨부 이미지 정리 스케줄 추가
- [x] 댓글 CRUD
- [x] 대댓글 CRUD
- [x] 공지 게시판 글쓰기 제한
- [x] 공지 게시글 댓글 제한
- [x] 게시글 조회수 증가
- [x] 게시글 검색
- [x] 페이지네이션
- [x] 게시글 / 인기글 목록의 인기 배지 표시

### Admin Basics

- [ ] 관리자 대시보드
- [ ] 상위분류 관리
- [ ] 게시판 관리
- [ ] 게시글 관리
- [ ] 댓글 관리
- [ ] 공지 관리
- [x] 홈 운영 공지 노출 기준 정리

## Phase 2 - Community Interaction And Moderation

### Interaction

- [x] 좋아요 토글
- [x] 인기글 계산 로직
- [x] 게시글 신고 드롭다운 / 검증 / 저장
- [x] 신고 처리 / 제재 결과 알림 생성
- [x] 알림 목록 페이지
- [x] 알림 전체 / 읽지 않음 필터
- [x] 모두 읽음 처리

### Moderation

- [x] 게시글 신고 접수 기능
- [x] 신고 / 운영 / 제재 모델 관계 정리
- [x] 신고 목록 관리자 화면
- [x] 콘텐츠 숨김 / 삭제 처리
- [x] 사용자 경고 처리
- [x] 사용자 기간 정지 처리
- [x] 사용자 영구 정지 처리
- [x] 운영 처리 이력 기록
- [x] 제재 이력 기록
- [x] 운영 사유 알림 발송

### Account Policy

- [ ] 회원 탈퇴 처리
- [ ] 탈퇴 후 로그인 차단
- [ ] 작성자 익명화 표시
- [ ] 완전 삭제 예약 시각 저장
- [ ] 스케줄러 기반 정리 구조 추가

## Phase 3 - Laravel Intermediate Features

### Structure

- [ ] Service 계층 분리
- [ ] Form Request 검증 분리
- [x] 공통 Blade 컴포넌트 정리
- [ ] 관리자/공개 컨트롤러 정리
- [ ] 글 작성 / 업로드 화면의 Alpine.js 인터랙션 정리

### API

- [ ] `GET /api/posts`
- [ ] `GET /api/posts/{id}`
- [ ] `GET /api/boards`
- [ ] `GET /api/me`
- [ ] Sanctum 인증 적용

### Event / Job

- [ ] 댓글 작성 이벤트
- [ ] 신고 접수 이벤트
- [ ] 운영 처리 이벤트
- [ ] 알림 또는 후처리 Job 분리

### Cache / Redis

- [x] 홈 인기글 캐시
- [ ] 게시판 최신글 캐시
- [ ] 알림 개수 캐시

### Broadcasting

- [ ] Reverb 설정
- [ ] Echo 설정
- [ ] 실시간 알림 기본 흐름 연결

### Testing

- [ ] 회원가입 / 로그인 테스트
- [ ] 게시글 생성 테스트
- [ ] 댓글 작성 테스트
- [ ] 신고 처리 테스트
- [ ] 관리자 접근 제어 테스트
- [ ] API 응답 테스트

## Done Criteria

- 회원이 가입하고 글과 댓글을 작성할 수 있다.
- 게시판 중심 커뮤니티 흐름이 완성되어 있다.
- 관리자가 신고, 게시글, 댓글, 공지를 운영할 수 있다.
- 제재와 숨김 처리에 사유와 이력이 남는다.
- Laravel 중급 기능이 실제 코드에 최소 한 번 이상 반영되어 있다.
