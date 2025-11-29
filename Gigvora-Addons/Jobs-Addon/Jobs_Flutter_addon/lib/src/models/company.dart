class Company {
  const Company({
    required this.id,
    required this.name,
    this.logoUrl,
    this.location,
    this.industry,
    this.size,
    this.website,
  });

  final int id;
  final String name;
  final String? logoUrl;
  final String? location;
  final String? industry;
  final String? size;
  final String? website;

  factory Company.fromJson(Map<String, dynamic> json) => Company(
        id: json['id'] as int,
        name: json['name'] as String,
        logoUrl: json['logo_path'] as String? ?? json['logo_url'] as String?,
        location: json['location'] as String?,
        industry: json['industry'] as String?,
        size: json['size'] as String?,
        website: json['website'] as String?,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'logo_url': logoUrl,
        'location': location,
        'industry': industry,
        'size': size,
        'website': website,
      };
}
