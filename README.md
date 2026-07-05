# CMS Orbit Popup

`cms-orbit/popup`은 Orbit 문서 엔진 위에 구축된 팝업 패키지입니다.  
관리자에서는 팝업 콘텐츠를 문서처럼 관리하고, 프런트에서는 활성 팝업만 골라 오버레이로 띄울 수 있습니다.

## 주요 기능

- `cms-orbit/core` 기반 문서형 팝업 콘텐츠
- Orbit 관리자 CRUD 자동 등록
- 노출 기간(`started_at`, `ended_at`) 관리
- "오늘 하루 보지 않기"를 위한 `ignore_days` 지원
- 스타일 JSON(`styles`) 기반 오버레이 커스터마이징
- 활성 팝업 조회 API와 `OrbitPopups` React 컴포넌트 제공

## 설치

```bash
composer require cms-orbit/popup:^4.0
php artisan migrate
php artisan orbit:frontend-sync   # orbit:install 직후라면 생략 가능
npm run dev                       # OrbitPopups 컴포넌트 개발 시
```

`cms-orbit/core`가 먼저 설치·설정(`orbit:install`)되어 있어야 합니다.

## Laravel Boost

이 패키지는 `resources/boost/guidelines/popup.md`, `resources/boost/skills/popup-development/`를 제공합니다.

- Boost **최초 설정**: 호스트에서 `php artisan boost:install` 1회
- **이후**: `orbit:install` / `orbit:sync`가 이 패키지를 Boost에 등록하고 `boost:update` 실행 (Boost가 이미 설정된 경우)

## 호스트 설정

| 작업 | 필수 여부 |
| --- | --- |
| `composer require cms-orbit/popup` + `php artisan migrate` | **필수** |
| `php artisan orbit:frontend-sync` | **필수** (`@cms-orbit/popup` alias) |
| 레이아웃에 `<OrbitPopups />` 1회 렌더 | **필수** (어느 공개 레이아웃에 띄울지는 호스트가 선택) |
| API 라우트 수동 등록 | **불필요** |

## 빠른 시작

### 1. 관리자에서 팝업 작성

관리자 화면에서 제목, 본문, 노출 시작/종료 시각, 다시 보지 않기 일수, 타이틀 노출 여부, 스타일 JSON을 관리할 수 있습니다.

### 2. 레이아웃에 오버레이 연결

호스트 레이아웃에 `OrbitPopups`를 한 번만 렌더링하면, 현재 시각 기준으로 활성 상태인 팝업을 자동 조회해 표시합니다.

```tsx
import { OrbitPopups } from '@cms-orbit/popup';

export function AppShell() {
    return (
        <>
            <OrbitPopups />
        </>
    );
}
```

기본 조회 엔드포인트는 `/popups/active`이며, 필요하면 `endpoint` prop으로 바꿀 수 있습니다.

```tsx
<OrbitPopups endpoint="/custom/popups/active" />
```

## 공개 엔드포인트

- `popups.active`

기본 경로:

```text
/popups/active
```

## 운영 팁

- `ignore_days`가 0 이하이면 세션 단위로만 닫힘 상태를 기억합니다.
- `ignore_days`가 1 이상이면 `localStorage`에 만료 시각을 저장해 "오늘 하루 보지 않기" 흐름을 구현합니다.
- `styles`에는 폭, 위치, 여백 같은 오버레이 스타일 값을 JSON으로 넣어 개별 팝업을 손쉽게 조정할 수 있습니다.

## License

MIT
