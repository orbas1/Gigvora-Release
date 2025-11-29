class Project {
  Project({
    required this.id,
    required this.title,
    required this.description,
    required this.budget,
    required this.type,
    required this.proposalsCount,
    this.slug,
    this.clientName,
  });

  final int id;
  final String? slug;
  final String title;
  final String description;
  final double budget;
  final String type;
  final int proposalsCount;
  final String? clientName;

  factory Project.fromJson(Map<String, dynamic> json) => Project(
        id: json['id'] as int,
        slug: json['slug'] as String?,
        title: json['title'] as String,
        description: json['description'] as String? ?? '',
        budget: (json['budget'] as num?)?.toDouble() ?? 0,
        type: json['type'] as String? ?? 'fixed',
        proposalsCount: json['proposals_count'] as int? ?? 0,
        clientName: json['client_name'] as String?,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        if (slug != null) 'slug': slug,
        'title': title,
        'description': description,
        'budget': budget,
        'type': type,
        'proposals_count': proposalsCount,
        'client_name': clientName,
      };
}
