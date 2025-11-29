class ScreeningQuestion {
  const ScreeningQuestion({required this.id, required this.prompt});

  final int id;
  final String prompt;

  factory ScreeningQuestion.fromJson(Map<String, dynamic> json) =>
      ScreeningQuestion(
        id: json['id'] as int,
        prompt: json['question'] as String? ?? json['prompt'] as String,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'prompt': prompt,
      };
}

class ScreeningAnswer {
  const ScreeningAnswer({required this.questionId, required this.answer});

  final int questionId;
  final String answer;

  factory ScreeningAnswer.fromJson(Map<String, dynamic> json) =>
      ScreeningAnswer(
        questionId:
            (json['screening_question_id'] as int?) ?? json['question_id'] as int,
        answer: json['answer'] as String,
      );

  Map<String, dynamic> toJson() => {
        'screening_question_id': questionId,
        'answer': answer,
      };
}
