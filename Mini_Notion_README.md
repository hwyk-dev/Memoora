
# Memoora – Notes App
Laravel 12 + TailwindCSS

## Project Overview
Memoora is a simple and modern note-taking web application built using Laravel 12 and TailwindCSS. 
The application allows users to create, edit, delete, and search notes with authentication and dark mode support.
The UI should be clean, modern, and responsive.

---

## Tech Stack
- Backend: Laravel 12
- Frontend: Blade + TailwindCSS
- Database: MySQL
- Authentication: Laravel Breeze
- Search: Basic keyword search (title + content)
- Theme: Light / Dark mode

---

## Core Features

### Authentication
- User Registration
- User Login
- Logout
- Password Reset (optional)

### Notes Management
- Create Note
- Edit Note
- Delete Note
- View Notes List
- Search Notes
- Pin Note
- Archive Note

### UI/UX
- Modern dashboard layout
- Sidebar navigation
- Notes displayed in card/grid layout
- Responsive design (mobile + desktop)
- Light Mode / Dark Mode toggle

---

## Database Design

### Users Table
Default Laravel users table.

### Notes Table
Columns:
- id
- user_id
- title
- content
- is_pinned (boolean)
- is_archived (boolean)
- created_at
- updated_at

### Optional Tables
#### tags
- id
- name

#### note_tag
- note_id
- tag_id

---

## Application Pages

### Public Pages
- Login
- Register

### Authenticated Pages
- Dashboard
- Notes List
- Create Note
- Edit Note
- Archived Notes
- Pinned Notes
- Settings/Profile

---

## Routes Structure
/login
/register
/dashboard
/notes
/notes/create
/notes/{id}/edit
/archive
/pinned
/settings
/logout

---

## Controllers
Suggested controllers:
- DashboardController
- NoteController
- SettingsController

### NoteController Functions
- index()
- create()
- store()
- edit()
- update()
- destroy()
- archive()
- pin()
- search()

---

## Dark Mode
Dark mode should be implemented using Tailwind dark class.
Store user theme preference in localStorage or database.

---

## Development Phases

### Phase 1 – Setup
- Install Laravel 12
- Install TailwindCSS
- Install Laravel Breeze
- Authentication setup
- Database setup

### Phase 2 – Notes CRUD
- Notes migration
- Note model
- Note controller
- Create/Edit/Delete notes
- Notes list UI

### Phase 3 – Features
- Search notes
- Pin notes
- Archive notes
- Dashboard layout
- Sidebar navigation

### Phase 4 – UI/UX
- Modern card design
- Responsive layout
- Dark mode
- Confirmation modals

### Phase 5 – Optional Improvements
- Tags
- Note colors
- Drag & drop notes
- Image upload
- Markdown editor
- Share notes

---

## Coding Guidelines
- Use Form Request Validation
- Follow Laravel naming conventions
- Use resource controllers
- Use soft delete for notes
- Use pagination for notes list
- Keep UI clean and minimal
- Write reusable Blade components

---

## Deliverables
- Authentication
- Notes CRUD
- Search
- Pin & Archive
- Dark Mode
- Responsive UI
- Proper database structure
- README documentation
- Migration files
- Seeder (optional)
