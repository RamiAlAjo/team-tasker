import React, { useState } from 'react';
import api from '../services/api';

const TaskForm = ({ onTaskCreated, initialData = null }) => {
  const [title, setTitle] = useState(initialData?.title || '');
  const [description, setDescription] = useState(initialData?.description || '');
  const [userId, setUserId] = useState(initialData?.user_id || '');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const taskData = { title, description, user_id: userId };
      const response = initialData
        ? await api.put(`/tasks/${initialData.id}`, taskData)
        : await api.post('/tasks', taskData);

      onTaskCreated(response.data);
      setTitle('');
      setDescription('');
      setUserId('');
    } catch (err) {
      setError(err.response?.data?.error || 'Failed to save task');
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handlesubmit}>
      <h3>{initialData ? 'Edit Task' : 'Create Task'}</h3>
      {error && <div className="error">{error}</div>}
      <div>
        <label>Title:</label>
        <input
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          required
        />
      </div>
      <div>
        <label>Description:</label>
        <textarea
          value={description}
          onChange={(e) => setDescription(e.target.value)}
        />
      </div>
      <div>
        <label>User ID:</label>
        <input
          type="number"
          value={userId}
          onChange={(e) => setUserId(e.target.value)}
          required
        />
      </div>
      <button type="submit" disabled={loading}>
        {loading ? 'Saving...' : initialData ? 'Update Task' : 'Create Task'}
      </button>
      {initialData && (
        <button type="button" onClick={() => onTaskCreated(null)}>
          Cancel
        </button>
      )}
    </form>
  );
};

export default TaskForm;