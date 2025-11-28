class SkillTag {
  final String name;
  final int? endorsementCount;

  const SkillTag({required this.name, this.endorsementCount});

  factory SkillTag.fromJson(Map<String, dynamic> json) => SkillTag(
        name: json['name'] as String? ?? json['tag'] as String? ?? '',
        endorsementCount: json['endorsements'] as int?,
      );

  Map<String, dynamic> toJson() => {
        'name': name,
        if (endorsementCount != null) 'endorsements': endorsementCount,
      };
}

class Experience {
  final String title;
  final String company;
  final DateTime? from;
  final DateTime? to;
  final bool current;

  const Experience({
    required this.title,
    required this.company,
    this.from,
    this.to,
    this.current = false,
  });

  factory Experience.fromJson(Map<String, dynamic> json) => Experience(
        title: json['title'] as String? ?? '',
        company: json['company'] as String? ?? '',
        from: json['from'] != null ? DateTime.tryParse(json['from'].toString()) : null,
        to: json['to'] != null ? DateTime.tryParse(json['to'].toString()) : null,
        current: json['current'] as bool? ?? false,
      );

  Map<String, dynamic> toJson() => {
        'title': title,
        'company': company,
        if (from != null) 'from': from!.toIso8601String(),
        if (to != null) 'to': to!.toIso8601String(),
        'current': current,
      };
}

class Education {
  final String school;
  final String qualification;
  final DateTime? from;
  final DateTime? to;

  const Education({
    required this.school,
    required this.qualification,
    this.from,
    this.to,
  });

  factory Education.fromJson(Map<String, dynamic> json) => Education(
        school: json['school'] as String? ?? '',
        qualification: json['qualification'] as String? ?? json['degree'] as String? ?? '',
        from: json['from'] != null ? DateTime.tryParse(json['from'].toString()) : null,
        to: json['to'] != null ? DateTime.tryParse(json['to'].toString()) : null,
      );

  Map<String, dynamic> toJson() => {
        'school': school,
        'qualification': qualification,
        if (from != null) 'from': from!.toIso8601String(),
        if (to != null) 'to': to!.toIso8601String(),
      };
}

class Certification {
  final String name;
  final String issuer;
  final DateTime? issuedAt;

  const Certification({required this.name, required this.issuer, this.issuedAt});

  factory Certification.fromJson(Map<String, dynamic> json) => Certification(
        name: json['name'] as String? ?? json['title'] as String? ?? '',
        issuer: json['issuer'] as String? ?? '',
        issuedAt: json['issued_at'] != null
            ? DateTime.tryParse(json['issued_at'].toString())
            : null,
      );

  Map<String, dynamic> toJson() => {
        'name': name,
        'issuer': issuer,
        if (issuedAt != null) 'issued_at': issuedAt!.toIso8601String(),
      };
}

class Reference {
  final String name;
  final String relationship;
  final String? contact;

  const Reference({required this.name, required this.relationship, this.contact});

  factory Reference.fromJson(Map<String, dynamic> json) => Reference(
        name: json['name'] as String? ?? '',
        relationship: json['relationship'] as String? ?? json['role'] as String? ?? '',
        contact: json['contact'] as String?,
      );

  Map<String, dynamic> toJson() => {
        'name': name,
        'relationship': relationship,
        if (contact != null) 'contact': contact,
      };
}

class ProfessionalProfile {
  final int? id;
  final String? headline;
  final String? location;
  final List<SkillTag> topSkills;
  final List<SkillTag> allSkills;
  final List<Experience> experience;
  final List<Education> education;
  final List<Certification> certifications;
  final List<Reference> references;
  final bool dbsChecked;
  final bool availableForWork;
  final List<String> interests;
  final String? publicUrl;

  const ProfessionalProfile({
    this.id,
    this.headline,
    this.location,
    this.topSkills = const [],
    this.allSkills = const [],
    this.experience = const [],
    this.education = const [],
    this.certifications = const [],
    this.references = const [],
    this.dbsChecked = false,
    this.availableForWork = false,
    this.interests = const [],
    this.publicUrl,
  });

  factory ProfessionalProfile.fromJson(Map<String, dynamic> json) {
    return ProfessionalProfile(
      id: json['id'] as int?,
      headline: json['headline'] as String? ?? json['header'] as String?,
      location: json['location'] as String?,
      topSkills: (json['top_skills'] as List? ?? json['topSkills'] as List? ?? const [])
          .map((e) => SkillTag.fromJson(e as Map<String, dynamic>))
          .toList(),
      allSkills: (json['skills'] as List? ?? const [])
          .map((e) => SkillTag.fromJson(e as Map<String, dynamic>))
          .toList(),
      experience: (json['experience'] as List? ?? const [])
          .map((e) => Experience.fromJson(e as Map<String, dynamic>))
          .toList(),
      education: (json['education'] as List? ?? const [])
          .map((e) => Education.fromJson(e as Map<String, dynamic>))
          .toList(),
      certifications: (json['certifications'] as List? ?? const [])
          .map((e) => Certification.fromJson(e as Map<String, dynamic>))
          .toList(),
      references: (json['references'] as List? ?? const [])
          .map((e) => Reference.fromJson(e as Map<String, dynamic>))
          .toList(),
      dbsChecked: json['dbs'] as bool? ?? json['dbs_checked'] as bool? ?? false,
      availableForWork: json['available_for_work'] as bool? ?? json['available'] as bool? ?? false,
      interests: List<String>.from(json['interests'] as List? ?? const []),
      publicUrl: json['public_url'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        if (id != null) 'id': id,
        if (headline != null) 'headline': headline,
        if (location != null) 'location': location,
        'top_skills': topSkills.map((e) => e.toJson()).toList(),
        'skills': allSkills.map((e) => e.toJson()).toList(),
        'experience': experience.map((e) => e.toJson()).toList(),
        'education': education.map((e) => e.toJson()).toList(),
        'certifications': certifications.map((e) => e.toJson()).toList(),
        'references': references.map((e) => e.toJson()).toList(),
        'dbs': dbsChecked,
        'available_for_work': availableForWork,
        'interests': interests,
        if (publicUrl != null) 'public_url': publicUrl,
      };
}

class CompanyProfile {
  final int? id;
  final String name;
  final String? description;
  final String? website;
  final String? location;
  final List<String> industries;
  final bool hiring;
  final int employeeCount;
  final List<Map<String, dynamic>> jobs;

  const CompanyProfile({
    this.id,
    required this.name,
    this.description,
    this.website,
    this.location,
    this.industries = const [],
    this.hiring = false,
    this.employeeCount = 0,
    this.jobs = const [],
  });

  factory CompanyProfile.fromJson(Map<String, dynamic> json, {int? employeeCount}) {
    return CompanyProfile(
      id: json['id'] as int?,
      name: json['name'] as String? ?? '',
      description: json['description'] as String?,
      website: json['website'] as String?,
      location: json['location'] as String?,
      industries: List<String>.from(json['industries'] as List? ?? json['tags'] as List? ?? const []),
      hiring: json['hiring'] as bool? ?? json['is_hiring'] as bool? ?? false,
      employeeCount: employeeCount ?? json['employee_count'] as int? ?? json['employees'] as int? ?? 0,
      jobs: List<Map<String, dynamic>>.from(json['jobs'] as List? ?? const []),
    );
  }

  Map<String, dynamic> toJson() => {
        if (id != null) 'id': id,
        'name': name,
        if (description != null) 'description': description,
        if (website != null) 'website': website,
        if (location != null) 'location': location,
        'industries': industries,
        'hiring': hiring,
        'employee_count': employeeCount,
        'jobs': jobs,
      };
}
