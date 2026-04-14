import React from 'react';

const App = () => {
  return (
    <div className="app">
      <header>
        <h1>Task Manager</h1>
      </header>
      <main>
        <div className="container">
          <TaskForm />
          <TaskList />
        </div>
      </main>
    </div>
  );
};

export default App;