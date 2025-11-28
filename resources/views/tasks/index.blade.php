<!doctype html>
<html lang="ca">
<head>
  <meta charset="utf-8">
  <title>📋 Task Demo</title>
  <style>
    /* Reset básico */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      max-width: 720px;
      margin: 2rem auto;
      padding: 0 1rem;
      background-color: #f0f4f8; /* Fondo suave azul grisáceo */
      color: #333;
    }

    h1 {
      text-align: center;
      margin-bottom: 2rem;
      color: #1f2937;
    }

    form {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    input[type=text] {
      flex: 1;
      padding: 0.5rem 1rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      font-size: 1rem;
    }

    input[type=text]:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    button {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 0.375rem;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    button:hover {
      opacity: 0.9;
    }

    button.add-btn {
      background-color: #3b82f6;
      color: #fff;
    }

    ul {
      list-style: none;
      margin-top: 1rem;
    }

    li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #ffffff;
      border: 1px solid #e5e7eb;
      padding: 0.75rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: 0.5rem;
      transition: transform 0.1s;
    }

    li:hover {
      transform: translateY(-2px);
    }

    .done {
      text-decoration: line-through;
      color: #9ca3af;
    }

    .error {
      color: #dc2626;
      margin-bottom: 1rem;
      font-weight: bold;
    }

    .toggle-btn {
      background-color: #10b981; /* Verde */
      color: #fff;
    }

    .delete-btn {
      background-color: #ef4444; /* Rojo */
      color: #fff;
    }

    .task-buttons {
      display: flex;
      gap: 0.5rem;
    }

    /* Modal personalizado */
    #confirmModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    #confirmModal .modal-content {
      background: #fff;
      padding: 2rem;
      border-radius: 0.5rem;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    #confirmModal button {
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }

    #confirmModal button#confirmYes {
      background-color: #ef4444;
      color: #fff;
      margin-right: 1rem;
    }

    #confirmModal button#confirmNo {
      background-color: #9ca3af;
      color: #fff;
    }

    @media (max-width: 500px) {
      body { padding: 0 0.5rem; }
      input[type=text], button { font-size: 0.9rem; }
    }
  </style>
</head>
<body>
  <h1>📋 Llista de tasques</h1>

  <!-- Formulario para añadir tarea -->
  <form method="POST" action="{{ route('tasks.store') }}">
    @csrf
    <input type="text" name="title" placeholder="Nova tasca..." required>
    <button type="submit" class="add-btn">Afegir</button>
  </form>

  @error('title')
    <p class="error">{{ $message }}</p>
  @enderror

  <!-- Lista de tareas -->
  <ul>
    @forelse($tasks as $task)
      <li>
        <span class="{{ $task->done ? 'done' : '' }}">{{ $task->title }}</span>
        <div class="task-buttons">
          <!-- Botón marcar/desmarcar -->
          <form method="POST" action="{{ route('tasks.toggle', $task) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="toggle-btn">{{ $task->done ? 'Desfer' : 'Fet' }}</button>
          </form>

          <!-- Botón eliminar -->
          <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Eliminar</button>
          </form>
        </div>
      </li>
    @empty
      <li>No hi ha tasques encara.</li>
    @endforelse
  </ul>

  <!-- Modal personalizado para confirmación -->
  <div id="confirmModal">
    <div class="modal-content">
      <p>Segur que vols eliminar aquesta tasca?</p>
      <button id="confirmYes">Sí, eliminar</button>
      <button id="confirmNo">Cancelar</button>
    </div>
  </div>

  <!-- Script para modal -->
  <script>
    const modal = document.getElementById('confirmModal');
    let formToSubmit;

    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault(); // evitar que el form se envíe
        formToSubmit = btn.closest('form');
        modal.style.display = 'flex'; // mostrar modal
      });
    });

    document.getElementById('confirmYes').addEventListener('click', () => {
      formToSubmit.submit(); // enviar el formulario si confirma
    });

    document.getElementById('confirmNo').addEventListener('click', () => {
      modal.style.display = 'none'; // cerrar modal si cancela
    });
  </script>
</body>
</html>
