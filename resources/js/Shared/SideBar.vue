<template>
  <ul
    class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
    id="accordionSidebar"
  >
    <!-- Sidebar - Brand -->
    <nav-link
      class="sidebar-brand d-flex align-items-center justify-content-center"
      href="/"
    >
      <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
      </div>
      <div class="sidebar-brand-text mx-3">
        SB Admin
        <sup>2</sup>
      </div>
    </nav-link>
    <!-- Divider -->
    <hr class="sidebar-divider my-0" />
    <li class="nav-item">
      <nav-link class="nav-link" href="/">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </nav-link>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
      <nav-link href="/categories">Category</nav-link>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
      <nav-link href="/authors">Authors</nav-link>
    </li>
    <li class="nav-item">
      <nav-link href="/languages">Languages</nav-link>
    </li>
    <li class="nav-item">
      <nav-link href="/publishers">Publishers</nav-link>
    </li>
    <li class="nav-item">
      <nav-link href="/members">Members</nav-link>
    </li>
    <li class="nav-item">
      <nav-link href="/books">Books</nav-link>
    </li>
    <li class="nav-item">
      <nav-link href="/borrowings">Book Borrowings</nav-link>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
  </ul>
</template>
<script setup></script>
