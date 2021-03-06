<?php

/*
 * Copyright (C) 2013 Kolibre
 *
 * This file is part of Kolibre-KADOS.
 * Kolibre-KADOS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * at your option) any later version.
 *
 * Kolibre-KADOS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Kolibre-KADOS. If not, see <http://www.gnu.org/licenses/>.
 */

$classmap = array(
		"logOn" => "logOn",
		"logOnResponse" => "logOnResponse",
		"logOff" => "logOff",
		"logOffResponse" => "logOffResponse",
		"setReadingSystemAttributes" => "setReadingSystemAttributes", // only in DODPv1
		"readingSystemAttributes" => "readingSystemAttributes",
		"config" => "config",
		"supportedContentFormats" => "supportedContentFormats",
		"supportedContentProtectionFormats" => "supportedContentProtectionFormats",
		"keyRing" => "keyRing",
		"supportedMimeTypes" => "supportedMimeTypes",
		"mimeType" => "mimeType",
		"supportedInputTypes" => "supportedInputTypes",
		"input" => "input",
		"additionalTransferProtocols" => "additionalTransferProtocols",
		"setReadingSystemAttributesResponse" => "setReadingSystemAttributesResponse", // only in DODPv1
		"issueContent" => "issueContent", // only in DODPv1
		"issueContentResponse" => "issueContentResponse", // only in DODPv1
		"getContentMetadata" => "getContentMetadata", // only in DODPv1
		"getContentMetadataResponse" => "getContentMetadataResponse", // only in DODPv1
		"contentMetadata" => "contentMetadata",
		"sample" => "sample",
		"metadata" => "metadata",
		"meta" => "meta",
		"getContentResources" => "getContentResources",
		"getContentResourcesResponse" => "getContentResourcesResponse",
		"resources" => "resources",
		"resource" => "resource",
		"package" => "package",
		"resourceRef" => "resourceRef",
		"getServiceAttributes" => "getServiceAttributes", // only in DODPv1
		"getServiceAttributesResponse" => "getServiceAttributesResponse", // only in DODPv1
		"serviceAttributes" => "serviceAttributes",
		"serviceProvider" => "serviceProvider",
		"label" => "label",
		"audio" => "audio",
		"service" => "service",
		"supportedUplinkAudioCodecs" => "supportedUplinkAudioCodecs",
		"supportedOptionalOperations" => "supportedOptionalOperations",
		"getContentList" => "getContentList",
		"getContentListResponse" => "getContentListResponse",
		"contentList" => "contentList",
		"contentItem" => "contentItem",
		"categoryLabel" => "categoryLabel",
		"subCategoryLabel" => "subCategoryLabel",
		"getServiceAnnouncements" => "getServiceAnnouncements",
		"getServiceAnnouncementsResponse" => "getServiceAnnouncementsResponse",
		"announcements" => "announcements",
		"announcement" => "announcement",
		"markAnnouncementsAsRead" => "markAnnouncementsAsRead",
		"read" => "read",
		"markAnnouncementsAsReadResponse" => "markAnnouncementsAsReadResponse",
		"returnContent" => "returnContent",
		"returnContentResponse" => "returnContentResponse",
		"setBookmarks" => "setBookmarks", // only in DODPv1
		"bookmarkObject" => "bookmarkObject",
		"bookmarkSet" => "bookmarkSet",
		"bookmarkAudio" => "bookmarkAudio",
		"title" => "title",
		"lastmark" => "lastmark",
		"bookmark" => "bookmark",
		"note" => "note",
		"hilite" => "hilite",
		"hiliteStart" => "hiliteStart",
		"hiliteEnd" => "hiliteEnd",
		"setBookmarksResponse" => "setBookmarksResponse", // only in DODPv1
		"updateBookmarks" => "updateBookmarks",
		"updateBookmarksResponse" => "updateBookmarksResponse",
		"getBookmarks" => "getBookmarks",
		"getBookmarksResponse" => "getBookmarksResponse",
		"getQuestions" => "getQuestions",
		"userResponses" => "userResponses",
		"userResponse" => "userResponse",
		"getQuestionsResponse" => "getQuestionsResponse",
		"questions" => "questions",
		"multipleChoiceQuestion" => "multipleChoiceQuestion",
		"choices" => "choices",
		"choice" => "choice",
		"inputQuestion" => "inputQuestion",
		"inputTypes" => "inputTypes",
		"getKeyExchangeObject" => "getKeyExchangeObject",
		"getKeyExchangeObjectResponse" => "getKeyExchangeObjectResponse",
		"KeyExchange" => "KeyExchange",
		"IssuerType" => "IssuerType",
		"KeyInfoType" => "KeyInfoType",
		"KeyValueType" => "KeyValueType",
		"DSAKeyValueType" => "DSAKeyValueType",
		"RSAKeyValueType" => "RSAKeyValueType",
		"RetrievalMethodType" => "RetrievalMethodType",
		"TransformsType" => "TransformsType",
		"TransformType" => "TransformType",
		"X509DataType" => "X509DataType",
		"X509IssuerSerialType" => "X509IssuerSerialType",
		"PGPDataType" => "PGPDataType",
		"SPKIDataType" => "SPKIDataType",
		"KeysType" => "KeysType",
		"KeyPairType" => "KeyPairType",
		"EncryptedType" => "EncryptedType",
		"EncryptionMethodType" => "EncryptionMethodType",
		"CipherDataType" => "CipherDataType",
		"CipherReferenceType" => "CipherReferenceType",
		"EncryptionPropertiesType" => "EncryptionPropertiesType",
		"EncryptionPropertyType" => "EncryptionPropertyType",
		"EncryptedKeyType" => "EncryptedKeyType",
		"ReferenceList" => "ReferenceList",
		"ReferenceType" => "ReferenceType",
		"UAKType" => "UAKType",
		"addContentToBookshelf" => "addContentToBookshelf",
		"addContentToBookshelfResponse" => "addContentToBookshelfResponse",
		"getUserCredentials" => "getUserCredentials",
		"credentials" => "credentials",
		"getUserCredentialsResponse" => "getUserCredentialsResponse",
		"getTermsOfService" => "getTermsOfService",
		"getTermsOfServiceResponse" => "getTermsOfServiceResponse",
		"acceptTermsOfService" => "acceptTermsOfService",
		"acceptTermsOfServiceResponse" => "acceptTermsOfServiceResponse",
		"setProgressState" => "setProgressState",
		"setProgressStateResponse" => "setProgressStateResponse"
		);
?>
