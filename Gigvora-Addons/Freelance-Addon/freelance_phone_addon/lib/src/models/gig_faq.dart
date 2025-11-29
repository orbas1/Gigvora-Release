class GigFaq {
  const GigFaq({
    required this.id,
    required this.question,
    required this.answer,
  });

  final int id;
  final String question;
  final String answer;

  factory GigFaq.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigFaq(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      question: data['question']?.toString() ?? '',
      answer: data['answer']?.toString() ?? '',
    );
  }
}
