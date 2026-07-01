# popup

CMS Orbit 팝업(Popup) 패키지. `cms-orbit/core`의 `DocumentModel` / `DocumentEntity` 엔진 위에 구축된 문서형 콘텐츠 타입입니다.

## 기능

- 중앙 `documents` / `document_contents` 테이블 기반의 다국어 팝업 콘텐츠
- Orbit 관리자 CRUD (`orbit.entities.popups.*`) 자동 등록
- 게시 기간(`started_at`, `ended_at`), 다시 보지 않기(`ignore_days`), 스타일(`styles`) 지원
- 현재 활성 팝업 조회 API (`popups.active`) 및 `OrbitPopups` 오버레이 컴포넌트

## 설치

```bash
composer require cms-orbit/popup
php artisan migrate
```

호스트 레이아웃에서 `@cms-orbit/popup`의 `OrbitPopups` 컴포넌트를 렌더링하면 활성 팝업이 표시됩니다.

## License

MIT
