class GigRequirement {
  const GigRequirement({
    required this.id,
    required this.prompt,
  });

  final int id;
  final String prompt;

  factory GigRequirement.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigRequirement(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      prompt: data['prompt']?.toString() ?? '',
    );
  }
}
