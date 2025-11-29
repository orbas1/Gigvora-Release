class GigChangeRequest {
  const GigChangeRequest({
    required this.id,
    required this.requester,
    required this.notes,
    required this.status,
  });

  final int id;
  final String requester;
  final String notes;
  final String status;

  factory GigChangeRequest.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigChangeRequest(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      requester: data['requester']?.toString() ?? '',
      notes: data['notes']?.toString() ?? '',
      status: data['status']?.toString() ?? 'pending',
    );
  }
}
