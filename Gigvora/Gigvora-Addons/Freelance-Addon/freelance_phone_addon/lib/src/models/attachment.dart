import 'package:equatable/equatable.dart';

class AttachmentFile extends Equatable {
  const AttachmentFile({
    required this.filePath,
    this.thumbPath,
  });

  final String filePath;
  final String? thumbPath;

  factory AttachmentFile.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const AttachmentFile(filePath: '');
    }
    return AttachmentFile(
      filePath: json['file_path']?.toString() ?? '',
      thumbPath: json['thumb_path']?.toString(),
    );
  }

  Map<String, dynamic> toJson() => {
        'file_path': filePath,
        'thumb_path': thumbPath,
      };

  @override
  List<Object?> get props => [filePath, thumbPath];
}

class AttachmentGroup extends Equatable {
  const AttachmentGroup({this.files = const []});

  final List<AttachmentFile> files;

  factory AttachmentGroup.fromJson(Map<String, dynamic>? json) {
    final files = (json?['files'] as List?)
            ?.map((file) => AttachmentFile.fromJson(file as Map<String, dynamic>?))
            .toList() ??
        const <AttachmentFile>[];
    return AttachmentGroup(files: files);
  }

  Map<String, dynamic> toJson() => {
        'files': files.map((file) => file.toJson()).toList(),
      };

  @override
  List<Object?> get props => [files];
}
