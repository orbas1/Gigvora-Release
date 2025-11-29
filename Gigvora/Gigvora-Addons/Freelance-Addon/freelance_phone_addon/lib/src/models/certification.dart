import 'package:equatable/equatable.dart';

class Certification extends Equatable {
  const Certification({
    required this.id,
    required this.userId,
    required this.name,
    this.issuer,
    this.credentialUrl,
    this.issuedAt,
    this.expiresAt,
  });

  final int id;
  final int userId;
  final String name;
  final String? issuer;
  final String? credentialUrl;
  final DateTime? issuedAt;
  final DateTime? expiresAt;

  factory Certification.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const Certification(id: 0, userId: 0, name: '');
    }

    DateTime? _parse(dynamic value) => value == null ? null : DateTime.tryParse(value.toString());

    return Certification(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '') ?? 0,
      userId: json['user_id'] is int ? json['user_id'] : int.tryParse(json['user_id']?.toString() ?? '') ?? 0,
      name: json['name']?.toString() ?? '',
      issuer: json['issuer']?.toString(),
      credentialUrl: json['credential_url']?.toString(),
      issuedAt: _parse(json['issued_at']),
      expiresAt: _parse(json['expires_at']),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'user_id': userId,
        'name': name,
        'issuer': issuer,
        'credential_url': credentialUrl,
        'issued_at': issuedAt?.toIso8601String(),
        'expires_at': expiresAt?.toIso8601String(),
      };

  @override
  List<Object?> get props => [id, userId, name, issuer, credentialUrl, issuedAt, expiresAt];
}
