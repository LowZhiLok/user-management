@extends('layouts.app')

@section('title', 'User Management')

@section('content')
@php
  $activeModal = $errors->any() ? old('form_mode') : null;
  $activeEditUserId = old('edit_user_id');
@endphp

<style>
  /* ========== GLOBAL STYLES ========== */
  * {
    box-sizing: border-box;
  }

  html, body {
    background-color: #f8fafc;
  }

  .page-wrapper {
    min-height: 100vh;
    background-color: #f8fafc;
    padding: 2rem 1rem;
  }

  .container-max {
    max-width: 1280px;
    margin: 0 auto;
  }

  /* ========== TYPOGRAPHY ========== */
  h1, h2, h3, h4, h5, h6 {
    color: #1e293b;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  }

  h1 {
    font-size: 2.25rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
  }

  h2 {
    font-size: 1.125rem;
    font-weight: 600;
  }

  p {
    color: #475569;
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
  }

  /* ========== ICONS: 16px-18px CONSTRAINT ========== */
  svg {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
  }

  svg.icon-large {
    width: 20px;
    height: 20px;
  }

  svg.icon-check {
    width: 18px;
    height: 18px;
  }

  /* ========== HEADER & PAGE TITLE ========== */
  header.page-header {
    margin-bottom: 2rem;
  }

  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
  }

  .header-text {
    flex: 1;
  }

  /* ========== SUCCESS ALERT ========== */
  .alert-success {
    background-color: #ecfdf5;
    border-left: 4px solid #10b981;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    animation: slideInDown 0.3s ease-out;
  }

  @keyframes slideInDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .alert-success p {
    color: #047857;
    font-weight: 500;
    margin: 0;
  }

  .alert-icon {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    color: #10b981;
  }

  /* ========== TOOLBAR & FLEXBOX LAYOUT ========== */
  .toolbar {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
  }

  .toolbar-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex: 0 1 auto;
  }

  .toolbar-filter {
    margin-left: auto;
    flex: 0 1 auto;
  }

  /* ========== BUTTONS & LINKS ========== */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    white-space: nowrap;
  }

  .btn:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
  }

  .btn-primary {
    background-color: #2563eb;
    color: white;
    box-shadow: 0 1px 3px rgba(37, 99, 235, 0.3);
  }

  .btn-primary:hover {
    background-color: #1d4ed8;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.4);
  }

  .btn-primary:active {
    transform: scale(0.98);
  }

  .btn-success {
    background-color: #10b981;
    color: white;
    box-shadow: 0 1px 3px rgba(16, 185, 129, 0.3);
  }

  .btn-success:hover {
    background-color: #059669;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.4);
  }

  .btn-secondary {
    background-color: white;
    color: #475569;
    border: 1px solid #cbd5e1;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  }

  .btn-secondary:hover {
    background-color: #f1f5f9;
    border-color: #94a3b8;
  }

  .btn-danger {
    background-color: #ef4444;
    color: white;
    box-shadow: 0 1px 3px rgba(239, 68, 68, 0.3);
  }

  .btn-danger:hover {
    background-color: #dc2626;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
  }

  .btn-small {
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
  }

  .btn-small svg {
    width: 16px;
    height: 16px;
  }

  /* ========== FILTER CARD ========== */
  .filter-card {
    background-color: white;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
  }

  .filter-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .filter-icon {
    color: #2563eb;
    flex-shrink: 0;
  }

  .filter-body {
    padding: 1.5rem;
  }

  .filter-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }

  @media (min-width: 768px) {
    .filter-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (min-width: 1024px) {
    .filter-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }

  .filter-group {
    display: flex;
    flex-direction: column;
  }

  .filter-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
  }

  .filter-group select,
  .filter-group input {
    padding: 0.625rem 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-family: inherit;
    background-color: white;
    color: #1e293b;
    transition: all 0.2s ease;
  }

  .filter-group select:focus,
  .filter-group input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .filter-actions {
    display: flex;
    gap: 0.75rem;
    align-items: flex-end;
    align-self: end;
    justify-content: flex-start;
  }

  .filter-actions .btn {
    min-width: 88px;
    justify-content: center;
    padding: 0.625rem 1rem;
  }

  @media (min-width: 1024px) {
    .filter-actions {
      max-width: 220px;
    }
  }

  /* ========== TABLE CARD ========== */
  .table-card {
    background-color: white;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .table-wrapper {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
  }

  thead {
    background-color: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
  }

  th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
  }

  th input[type="checkbox"] {
    cursor: pointer;
    width: 18px;
    height: 18px;
  }

  tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: background-color 0.15s ease;
  }

  tbody tr:hover {
    background-color: #f0f9ff;
  }

  td {
    padding: 1rem;
    color: #1e293b;
  }

  td input[type="checkbox"] {
    cursor: pointer;
    width: 18px;
    height: 18px;
    accent-color: #2563eb;
  }

  /* ========== USER AVATAR ========== */
  .user-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    flex-shrink: 0;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
  }

  .user-name {
    font-weight: 600;
    color: #1e293b;
  }

  .user-email,
  .user-phone {
    color: #64748b;
  }

  /* ========== STATUS BADGE ========== */
  .badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .badge-active {
    background-color: #d1fae5;
    color: #065f46;
  }

  .badge-inactive {
    background-color: #fed7aa;
    color: #92400e;
  }

  .badge-trashed {
    background-color: #e5e7eb;
    color: #374151;
  }

  /* ========== ACTION BUTTONS IN TABLE ========== */
  .action-cell {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
  }

  .btn-action {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    text-decoration: none;
  }

  .btn-action-edit {
    background-color: #dbeafe;
    color: #1e40af;
  }

  .btn-action-edit:hover {
    background-color: #3b82f6;
    color: white;
  }

  .btn-action-delete {
    background-color: #fee2e2;
    color: #991b1b;
  }

  .btn-action-delete:hover {
    background-color: #ef4444;
    color: white;
  }

  .btn-action-restore {
    background-color: #d1fae5;
    color: #065f46;
  }

  .btn-action-restore:hover {
    background-color: #10b981;
    color: white;
  }

  /* ========== MODALS ========== */
  body.modal-open {
    overflow: hidden;
  }

  .modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 50;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background-color: rgba(15, 23, 42, 0.55);
  }

  .modal-backdrop.is-open {
    display: flex;
  }

  .modal-panel {
    width: min(100%, 640px);
    max-height: 90vh;
    overflow-y: auto;
    background-color: white;
    border-radius: 0.75rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 24px 80px rgba(15, 23, 42, 0.25);
  }

  .modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
  }

  .modal-title {
    margin: 0 0 0.25rem 0;
  }

  .modal-close {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 0.5rem;
    background-color: #f1f5f9;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .modal-close:hover {
    background-color: #e2e8f0;
    color: #1e293b;
  }

  .modal-body {
    padding: 1.5rem;
  }

  .modal-form {
    display: grid;
    gap: 1rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
  }

  .form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
  }

  .required-star {
    color: #dc2626;
    font-weight: 700;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 0.625rem 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-family: inherit;
    color: #1e293b;
    background-color: white;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding-top: 0.5rem;
  }

  .alert-error {
    background-color: #fff1f2;
    border: 1px solid #fecdd3;
    border-radius: 0.5rem;
    color: #9f1239;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    padding: 1rem;
  }

  .alert-error ul {
    margin: 0;
    padding-left: 1.25rem;
  }

  /* ========== EMPTY STATE ========== */
  .empty-state {
    padding: 4rem 2rem;
    text-align: center;
  }

  .empty-icon {
    width: 64px;
    height: 64px;
    color: #cbd5e1;
    margin: 0 auto 1rem auto;
  }

  .empty-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
  }

  .empty-text {
    color: #94a3b8;
    margin-bottom: 1.5rem;
  }

  /* ========== TABLE FOOTER ========== */
  .table-footer {
    padding: 1.5rem;
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  @media (min-width: 768px) {
    .table-footer {
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
    }
  }

  .pagination {
    display: flex;
    justify-content: center;
    gap: 0.25rem;
    align-items: center;
  }

  .pagination a,
  .pagination span {
    padding: 0.375rem 0.625rem;
    border-radius: 0.375rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #2563eb;
    text-decoration: none;
    border: 1px solid transparent;
    transition: all 0.2s ease;
  }

  .pagination a:hover {
    background-color: #e0e7ff;
    border-color: #2563eb;
  }

  .pagination span.active {
    background-color: #2563eb;
    color: white;
    border-color: #2563eb;
  }

  .pagination span.disabled {
    color: #cbd5e1;
    cursor: not-allowed;
  }

  /* ========== RESPONSIVE ========== */
  @media (max-width: 768px) {
    .toolbar {
      flex-direction: column;
    }

    .toolbar-actions {
      width: 100%;
    }

    .toolbar-filter {
      width: 100%;
      margin-left: 0;
    }

    .toolbar-filter .btn {
      width: 100%;
    }

    .filter-actions {
      flex-direction: column;
    }

    .modal-actions {
      flex-direction: column-reverse;
    }

    .modal-actions .btn {
      justify-content: center;
      width: 100%;
    }

    th, td {
      padding: 0.75rem 0.5rem;
      font-size: 0.8125rem;
    }

    .table-footer {
      gap: 1rem;
    }
  }
</style>

<div class="page-wrapper">
  <div class="container-max">

    <!-- PAGE HEADER -->
    <header class="page-header">
      <div class="header-text">
        <h1>User Management</h1>
      </div>
    </header>

    <!-- SUCCESS ALERT -->
    @if(session('success'))
      <div class="alert-success">
        <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p>{{ session('success') }}</p>
      </div>
    @endif

    @if($errors->any() && !$activeModal)
      <div class="alert-error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- TOOLBAR WITH FLEXBOX -->
    <section class="toolbar">
      <div class="toolbar-actions">
        <button type="button" class="btn btn-primary" data-modal-target="create-user-modal">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add New User
        </button>
        <form action="{{ route('users.export') }}" method="GET" style="display: inline;">
          <input type="hidden" name="name" value="{{ request('name') }}">
          <input type="hidden" name="status" value="{{ request('status') }}">
          <input type="hidden" name="trashed" value="{{ request('trashed') }}">
          <button type="submit" class="btn btn-success">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export to Excel
          </button>
        </form>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
          Refresh
        </a>
      </div>
      <div class="toolbar-filter">
        <button class="btn btn-secondary" onclick="document.getElementById('filter-form').style.display = document.getElementById('filter-form').style.display === 'none' ? 'block' : 'none';">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          Filter
        </button>
      </div>
    </section>

    <!-- FILTER CARD -->
    <section class="filter-card" id="filter-form">
      <div class="filter-header">
        <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        <h2>Filter Users</h2>
      </div>
      <div class="filter-body">
        <form method="GET" action="{{ route('users.index') }}">
          <div class="filter-grid">
            <div class="filter-group">
              <label for="name">Name</label>
              <input id="name" type="search" name="name" placeholder="Search user name" value="{{ request('name') }}" />
            </div>
            <div class="filter-group">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="">All Users</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
              </select>
            </div>
            <div class="filter-group">
              <label for="trashed">Trashed</label>
              <select id="trashed" name="trashed">
                <option value="0" {{ request('trashed') ? '' : 'selected' }}>No</option>
                <option value="1" {{ request('trashed') ? 'selected' : '' }}>Yes</option>
              </select>
            </div>
            <div class="filter-group">
              <label for="per-page">Per Page</label>
              <input id="per-page" type="number" name="per_page" min="1" max="100" value="{{ request('per_page', 15) }}" />
            </div>
            <div class="filter-actions">
              <button type="submit" class="btn btn-primary">Apply</button>
              <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
            </div>
          </div>
        </form>
      </div>
    </section>
    <!-- DATA TABLE CARD -->
    <section class="table-card">
      <form id="bulk-delete-form" method="POST" action="{{ route('users.bulkDestroy') }}">
        @csrf
        @method('DELETE')
      </form>

      <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th style="width: 50px; text-align: center;">
                  <input type="checkbox" id="select-all" class="icon-check" />
                </th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Joined</th>
                <th style="text-align: center; width: 120px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
                <tr>
                  <td style="text-align: center;">
                    @if(!$user->trashed())
                      <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="icon-check" form="bulk-delete-form" />
                    @endif
                  </td>
                  <td>
                    <div class="user-cell">
                      <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                      <div class="user-name">{{ $user->name }}</div>
                    </div>
                  </td>
                  <td>
                    <div class="user-email">{{ $user->email }}</div>
                  </td>
                  <td>
                    <div class="user-phone">{{ $user->phone_number }}</div>
                  </td>
                  <td>
                    @if($user->trashed())
                      <span class="badge badge-trashed">🗑️ Trashed</span>
                    @else
                      <span class="badge {{ $user->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                        {{ $user->status === 'active' ? '🟢 Active' : '🔴 Inactive' }}
                      </span>
                    @endif
                  </td>
                  <td>{{ $user->created_at?->format('M d, Y') ?? '—' }}</td>
                  <td>
                    <div class="action-cell">
                      @if($user->trashed())
                        <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display: inline;">
                          @csrf
                          <button type="submit" class="btn-action btn-action-restore">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15L3 9m0 0l6-6m-6 6h18a9 9 0 010 18H9"/>
                            </svg>
                            Restore
                          </button>
                        </form>
                      @else
                        <button type="button" class="btn-action btn-action-edit" data-modal-target="edit-user-modal-{{ $user->id }}">
                          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                          </svg>
                          Edit
                        </button>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn-action btn-action-delete">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7">
                    <div class="empty-state">
                      <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                      </svg>
                      <h3 class="empty-title">No users yet</h3>
                      <p class="empty-text">Start by creating your first user</p>
                      <button type="button" class="btn btn-primary" data-modal-target="create-user-modal">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create User
                      </button>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- TABLE FOOTER -->
        <div class="table-footer">
          <button type="submit" class="btn btn-danger" form="bulk-delete-form">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete Selected
          </button>
          <div class="pagination">
            {{ $users->withQueryString()->links() }}
          </div>
        </div>
    </section>

  </div>
</div>

<div id="create-user-modal" class="modal-backdrop {{ $activeModal === 'create' ? 'is-open' : '' }}" role="dialog" aria-modal="true" aria-labelledby="create-user-title">
  <div class="modal-panel">
    <div class="modal-header">
      <div>
        <h2 id="create-user-title" class="modal-title">Create User</h2>
      </div>
      <button type="button" class="modal-close" data-modal-close aria-label="Close create user modal">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="modal-body">
      @if ($errors->any() && $activeModal === 'create')
        <div class="alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('users.store') }}" class="modal-form">
        @csrf
        <input type="hidden" name="form_mode" value="create">

        <div class="form-group">
          <label for="create-name">Name <span class="required-star">*</span></label>
          <input id="create-name" type="text" name="name" value="{{ $activeModal === 'create' ? old('name') : '' }}" required>
        </div>

        <div class="form-group">
          <label for="create-email">Email <span class="required-star">*</span></label>
          <input id="create-email" type="email" name="email" value="{{ $activeModal === 'create' ? old('email') : '' }}" required>
        </div>

        <div class="form-group">
          <label for="create-phone">Phone Number <span class="required-star">*</span></label>
          <input id="create-phone" type="tel" name="phone_number" inputmode="numeric" pattern="[0-9]{7,15}" minlength="7" maxlength="15" data-digits-only value="{{ $activeModal === 'create' ? old('phone_number') : '' }}" required>
        </div>

        <div class="form-group">
          <label for="create-password">Password <span class="required-star">*</span></label>
          <input id="create-password" type="password" name="password" required>
        </div>

        <div class="form-group">
          <label for="create-status">Status <span class="required-star">*</span></label>
          <select id="create-status" name="status" required>
            <option value="active" {{ ($activeModal === 'create' ? old('status') : '') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ ($activeModal === 'create' ? old('status') : '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <div class="modal-actions">
          <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
          <button type="submit" class="btn btn-primary">Create User</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach ($users as $user)
  @if(!$user->trashed())
    @php
      $isActiveEditModal = $activeModal === 'edit' && (string) $activeEditUserId === (string) $user->id;
    @endphp
    <div id="edit-user-modal-{{ $user->id }}" class="modal-backdrop {{ $isActiveEditModal ? 'is-open' : '' }}" role="dialog" aria-modal="true" aria-labelledby="edit-user-title-{{ $user->id }}">
      <div class="modal-panel">
        <div class="modal-header">
          <div>
            <h2 id="edit-user-title-{{ $user->id }}" class="modal-title">Edit User</h2>
            <p>Update details and status for {{ $user->name }}.</p>
          </div>
          <button type="button" class="modal-close" data-modal-close aria-label="Close edit user modal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <div class="modal-body">
          @if ($errors->any() && $isActiveEditModal)
            <div class="alert-error">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('users.update', $user) }}" class="modal-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_mode" value="edit">
            <input type="hidden" name="edit_user_id" value="{{ $user->id }}">

            <div class="form-group">
              <label for="edit-name-{{ $user->id }}">Name <span class="required-star">*</span></label>
              <input id="edit-name-{{ $user->id }}" type="text" name="name" value="{{ $isActiveEditModal ? old('name', $user->name) : $user->name }}" required>
            </div>

            <div class="form-group">
              <label for="edit-email-{{ $user->id }}">Email <span class="required-star">*</span></label>
              <input id="edit-email-{{ $user->id }}" type="email" name="email" value="{{ $isActiveEditModal ? old('email', $user->email) : $user->email }}" required>
            </div>

            <div class="form-group">
              <label for="edit-phone-{{ $user->id }}">Phone Number <span class="required-star">*</span></label>
              <input id="edit-phone-{{ $user->id }}" type="tel" name="phone_number" inputmode="numeric" pattern="[0-9]{7,15}" minlength="7" maxlength="15" data-digits-only value="{{ $isActiveEditModal ? old('phone_number', $user->phone_number) : $user->phone_number }}" required>
            </div>

            <div class="form-group">
              <label for="edit-password-{{ $user->id }}">Password</label>
              <input id="edit-password-{{ $user->id }}" type="password" name="password" placeholder="Leave blank to keep current password">
            </div>

            <div class="form-group">
              <label for="edit-status-{{ $user->id }}">Status <span class="required-star">*</span></label>
              <select id="edit-status-{{ $user->id }}" name="status" required>
                @php
                  $selectedStatus = $isActiveEditModal ? old('status', $user->status) : $user->status;
                @endphp
                <option value="active" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>

            <div class="modal-actions">
              <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
              <button type="submit" class="btn btn-primary">Update User</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endforeach

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');
    const openButtons = document.querySelectorAll('[data-modal-target]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');
    const openModals = document.querySelectorAll('.modal-backdrop.is-open');
    const digitOnlyInputs = document.querySelectorAll('[data-digits-only]');

    function openModal(modal) {
      if (!modal) {
        return;
      }

      modal.classList.add('is-open');
      document.body.classList.add('modal-open');
      const firstInput = modal.querySelector('input:not([type="hidden"]), select, button');

      if (firstInput) {
        firstInput.focus();
      }
    }

    function closeModal(modal) {
      if (!modal) {
        return;
      }

      modal.classList.remove('is-open');

      if (!document.querySelector('.modal-backdrop.is-open')) {
        document.body.classList.remove('modal-open');
      }
    }

    if (selectAll) {
      selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (checkbox) {
          checkbox.checked = selectAll.checked;
        });
      });
    }

    if (bulkDeleteForm) {
      bulkDeleteForm.addEventListener('submit', function (event) {
        const selectedCount = document.querySelectorAll('input[name="ids[]"]:checked').length;

        if (selectedCount === 0) {
          event.preventDefault();
          alert('Please select at least one user to delete.');
          return;
        }

        if (!confirm('Delete selected users?')) {
          event.preventDefault();
        }
      });
    }

    digitOnlyInputs.forEach(function (input) {
      input.addEventListener('input', function () {
        input.value = input.value.replace(/\D/g, '');
      });
    });

    openButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        openModal(document.getElementById(button.dataset.modalTarget));
      });
    });

    closeButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        closeModal(button.closest('.modal-backdrop'));
      });
    });

    document.querySelectorAll('.modal-backdrop').forEach(function (modal) {
      modal.addEventListener('click', function (event) {
        if (event.target === modal) {
          closeModal(modal);
        }
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeModal(document.querySelector('.modal-backdrop.is-open'));
      }
    });

    if (openModals.length > 0) {
      document.body.classList.add('modal-open');
    }
  });
</script>
@endsection
