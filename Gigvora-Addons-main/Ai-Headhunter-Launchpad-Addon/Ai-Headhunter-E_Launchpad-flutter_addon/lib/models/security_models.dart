class SecurityEvent {
  final String id;
  final String type;
  final String description;
  final DateTime occurredAt;
  final Map<String, dynamic>? context;

  const SecurityEvent({
    required this.id,
    required this.type,
    required this.description,
    required this.occurredAt,
    this.context,
  });

  factory SecurityEvent.fromJson(Map<String, dynamic> json) {
    return SecurityEvent(
      id: json['id']?.toString() ?? '',
      type: json['type'] as String? ?? 'event',
      description: json['description'] as String? ?? '',
      occurredAt: DateTime.tryParse(json['occurred_at']?.toString() ?? '') ??
          DateTime.tryParse(json['created_at']?.toString() ?? '') ??
          DateTime.now(),
      context: json['context'] as Map<String, dynamic>?,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'type': type,
        'description': description,
        'occurred_at': occurredAt.toIso8601String(),
        if (context != null) 'context': context,
      };
}

class SecurityStatus {
  final bool mfaEnabled;
  final bool alertsEnabled;

  const SecurityStatus({
    this.mfaEnabled = false,
    this.alertsEnabled = false,
  });

  factory SecurityStatus.fromJson(Map<String, dynamic> json) {
    return SecurityStatus(
      mfaEnabled: json['mfa_enabled'] as bool? ?? json['mfaEnabled'] as bool? ?? false,
      alertsEnabled:
          json['alerts_enabled'] as bool? ?? json['alertsEnabled'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        'mfa_enabled': mfaEnabled,
        'alerts_enabled': alertsEnabled,
      };
}

class AgeVerificationStatus {
  final String status;
  final DateTime? verifiedAt;
  final String? provider;
  final String? providerReference;

  const AgeVerificationStatus({
    required this.status,
    this.verifiedAt,
    this.provider,
    this.providerReference,
  });

  factory AgeVerificationStatus.fromJson(Map<String, dynamic> json) {
    return AgeVerificationStatus(
      status: json['status'] as String? ?? 'unverified',
      verifiedAt: json['verified_at'] != null
          ? DateTime.tryParse(json['verified_at'].toString())
          : null,
      provider: json['provider'] as String?,
      providerReference: json['provider_reference'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'status': status,
        if (verifiedAt != null) 'verified_at': verifiedAt!.toIso8601String(),
        if (provider != null) 'provider': provider,
        if (providerReference != null) 'provider_reference': providerReference,
      };
}
