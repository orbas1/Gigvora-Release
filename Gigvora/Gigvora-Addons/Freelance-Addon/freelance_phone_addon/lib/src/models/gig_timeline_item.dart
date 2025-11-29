class GigTimelineItem {
  const GigTimelineItem({
    required this.id,
    required this.title,
    required this.description,
    required this.occurredAt,
  });

  final int id;
  final String title;
  final String? description;
  final DateTime? occurredAt;

  factory GigTimelineItem.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigTimelineItem(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      title: data['title']?.toString() ?? '',
      description: data['description']?.toString(),
      occurredAt: data['occurred_at'] != null ? DateTime.tryParse(data['occurred_at'].toString()) : null,
    );
  }
}
