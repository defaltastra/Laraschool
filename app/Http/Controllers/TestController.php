<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::all();
        return view('tests.index', compact('tests'));
    }

    public function create()
    {
        return view('tests.create');
    }

    public function store(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'questions' => 'required|array',
        'questions.*.text' => 'required|string',
        'questions.*.points' => 'required|integer|min:1|max:20',
        'questions.*.answers' => 'required|array',
        'questions.*.answers.*' => 'required|string',
        'questions.*.correct' => 'required|integer|in:0,1,2,3', // Correct answer index
    ]);

    // Create the test
    $test = Test::create([
        'name' => $request->name,
        'description' => $request->description,
        'teacher_id' => auth()->id(),
    ]);

    // Loop through each question and store it with the answers
    foreach ($request->questions as $questionData) {
        $question = Question::create([
            'test_id' => $test->id,
            'text' => $questionData['text'],
            'points' => $questionData['points'],
        ]);

        // Store the answers for this question
        foreach ($questionData['answers'] as $index => $answerText) {
            $isCorrect = $index == $questionData['correct']; // Mark the correct answer
            Answer::create([
                'question_id' => $question->id,
                'text' => $answerText,
                'is_correct' => $isCorrect,
            ]);
        }
    }

    return redirect()->route('tests.list')->with('success', 'Test created successfully.');
}


public function show(Test $test)
{
    // Eager load questions with answers for performance optimization
    $test->load('questions.answers'); 
    
    return view('tests.show', compact('test'));
}

    public function edit(Test $test)
    {
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $test->update($request->only('name', 'description'));

        return redirect()->route('tests.list')->with('success', 'Test updated successfully.');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('tests.list')->with('success', 'Test deleted successfully.');
    }

    public function results()
    {
        $results = TestResult::with('test', 'student')->get();
        return view('tests.results', compact('results'));
    }

    // Add the submit method to handle the test submission
   
    public function submit(Request $request, Test $test)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to submit the test.');
        }
    
        $user = auth()->user();
        
        // Validate answers
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:answers,id', // Ensure answers are valid
        ]);
    
        $score = 0;
        $totalPoints = 0;
        
        foreach ($test->questions as $question) {
            $correctAnswer = $question->answers->where('is_correct', true)->first();
            $submittedAnswer = $request->input("answers.{$question->id}");
    
            if ($submittedAnswer === null) {
                continue; // Skip if no answer was selected
            }
    
            // Check if submitted answer matches the correct one
            if ($submittedAnswer == $correctAnswer->id) {
                $score += $question->points; // Add points for correct answers
            }
    
            // Sum the points for the total score
            $totalPoints += $question->points;
        }
    
        // Save the result
        TestResult::create([
            'test_id' => $test->id,
            'user_id' => $user->id,
            'score' => $score,
            'total_points' => $totalPoints,
        ]);
    
        return redirect()->route('tests.list')->with('success', "Test submitted. Your score: {$score} / {$totalPoints}");
    }
    

}
