DateTime? parseDateTime(dynamic value) {
  if (value == null) return null;
  try {
    return DateTime.parse(value.toString());
  } catch (_) {
    return null;
  }
}

Map<String, dynamic>? parseMetadata(dynamic value) {
  if (value == null) return null;
  return Map<String, dynamic>.from(value as Map);
}
