// API routes for Task Manager

const express = require('express');
const router = express.Router();
const { ensureAuth } = require('../middleware/auth');

// @desc    Get all tasks
// @route   GET /api/tasks
router.get('/', ensureAuth, async (req, res) => {
    try {
        const tasks = await Task.find({ user: req.user.id }).sort({ createdAt: -1 });
        res.json(tasks);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
});

// @desc    Create new task
// @route   POST /api/tasks
router.post('/', ensureAuth, async (req, res) => {
    const { title, description } = req.body;

    try {
        const newTask = new Task({
            title,
            description,
            user: req.user.id
        });

        const task = await newTask.save();
        res.json(task);
    } catch (err) {
        console.error(err.message);
        res.status(500).send('Server Error');
    }
});

// @desc    Update task
// @route   PUT /api/tasks/:id
router.put('/:id', ensureAuth, async (req, res) => {
    const { title, description, status } = req.body;

    try {
        let task = await Task.findById(req.params.id);

        if (!task) {
            return res.status(404).json({ msg: 'Task not found' });
        }

        // Check user ownership
        if (task.user.toString() !== req.user.id) {
            return res.status(401).json({ msg: 'Not authorized' });
        }

        const taskUpdates = {};
        if (title) taskUpdates.title = title;
        if (description) taskUpdates.description = description;
        if (status) taskUpdates.status = status;

        task = await Task.findByIdAndUpdate(
            req.params.id,
            { $set: taskUpdates },
            { new: true }
        );

        res.json(task);
    } catch (err) {
        console.error(err.message);
        if (err.kind === 'ObjectId') {
            return res.status(404).json({ msg: 'Task not found' });
        }
        res.status(500).send('Server Error');
    }
});

// @desc    Delete task
// @route   DELETE /api/tasks/:id
router.delete('/:id', ensureAuth, async (req, res) => {
    try {
        const task = await Task.findById(req.params.id);

        if (!task) {
            return res.status(404).json({ msg: 'Task not found' });
        }

        // Check user ownership
        if (task.user.toString() !== req.user.id) {
            return res.status(401).json({ msg: 'Not authorized' });
        }

        await task.remove();
        res.json({ msg: 'Task removed' });
    } catch (err) {
        console.error(err.message);
        if (err.kind === 'ObjectId') {
            return res.status(404).json({ msg: 'Task not found' });
        }
        res.status(500).send('Server Error');
    }
});

module.exports = router;