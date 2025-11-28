class ProjectSubmission {
  const ProjectSubmission({
    required this.id,
    required this.notes,
    required this.attachmentUrl,
    required this.status,
  });

  final int id;
  final String notes;
  final String? attachmentUrl;
  final String status;

  factory ProjectSubmission.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return ProjectSubmission(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      notes: data['notes']?.toString() ?? '',
      attachmentUrl: data['attachment_url']?.toString(),
      status: data['status']?.toString() ?? 'submitted',
    );
  }
}
